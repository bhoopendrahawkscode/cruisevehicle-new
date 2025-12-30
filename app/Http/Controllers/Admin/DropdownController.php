<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use App\Models\Common;
use App\Models\CommonTranslation;
use App\Http\Requests\DropdownRequest;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Constants\Constant;
use Config;
use App\Services\CommonService;
use App\Services\FilterService;
use App\Models\Dropdown;
use Illuminate\Support\Facades\Storage;
use \App\models\StandoutTranslation;

class DropdownController extends BaseController
{
    protected $imageFolder;
    protected $thumb;
    protected $dropdownUrl;
    protected $dropdownName;

    protected $translationTableName;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolder = Config::get('constants.DROPDOWN_FOLDER');
        $this->thumb  = '/thumb-';
        $this->dropdownUrl = '/admin/dropdown/index/';
        $this->translationTableName = '_translations';
        $this->dropdownName = 'dropdowns.';
    }


    public function add($type)
    {
        $nameExists = Dropdown::where('name', $type)->exists();
        if (!$nameExists) {

            return abort(403);
        }
        CommonService::createTableIfNotExists($type);
        $image_exist = Dropdown::where('name', $type)->first();
        return view('admin.dropdown.add', compact('type', 'image_exist'));
    }

    public function save($type, DropdownRequest $request)
    {
        CommonTranslation::setGlobalTable($type . $this->translationTableName);
        $common = (new Common)->setTable($type);
        $fileName = '';

        if (!empty($request->image)) {
            $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolder . '/' . $type . '/');
            ImageService::manipulateImage(Constant::OPERATION_TYPE, $request->image, $this->imageFolder . '' . $type . '/', 150, 100, $fileName);
        }
        $lastInsertedId = $common->insertGetId(['image' => $fileName]);
        $translations = [];
        foreach ($request->name as $key => $value) {
            $translations[] = [
                $type . '_id' => $lastInsertedId,
                'language_id' => $key,
                'name' => $value,
            ];
        }
        (new CommonTranslation)->setTable($type . $this->translationTableName)->insert($translations);

        return redirect($this->dropdownUrl . $type)
            ->with('success', ucwords(__($this->dropdownName . str_replace("_", " ", $type))) . trans('messages.recordAddedSpecific'));
    }

    public function index($type, Request $request)
    {

        $nameExists = CommonService::getNameExists($type);

        if (!$nameExists) {
            return abort(403);
        }

        $image_exist = CommonService::getImageExists($type);

        $DB = (new Common)->setTable($type)->newQuery();
        CommonService::createTableIfNotExists($type); //PGupta
        $fieldsToSearch              =   array('name' => 'like', 'status' => '=');
        $searchVariable                =    $request->all();

        $output                     =    FilterService::getFiltersDropdownsLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $type,
                "foreignKey" => $type . '_id',
                "translationFields" => [$type . '.*', $type . '_translations.name']
            ]
        );
        extract($output);
        return  View('admin.dropdown.index', compact('type', 'image_exist', 'result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

    public function edit($id, $type)
    {
        $nameExists = Dropdown::where('name', $type)->exists();
        if (!$nameExists) {
            return abort(403);
        }
        $image_exist = Dropdown::where('name', $type)->first();

        $DB     = (new Common)->setTable($type);

        CommonTranslation::setGlobalTable($type . $this->translationTableName);
        $data =  $DB->where('id', $id)->with('dropdown_translations')->first();

        if (!$data) {
            return abort(403);
        }

        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['thumbImage'] = $data->thumbImage;
        if (!empty($data['dropdown_translations'])) {
            foreach ($data['dropdown_translations'] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }

        return view('admin.dropdown.edit')->with(['data' => $dataReArrange, 'type' => $type, 'image_exist' => $image_exist]);
    }

    public function update($Id, $type, DropdownRequest  $request)
    {

        $data = [];
        if (!empty($request->image)) {
            $temp = (new Common)->setTable($type)->where('id', $Id)->value('image');
            $fileName  = ImageService::fileUploadImage($request->image, $temp, $this->imageFolder . '' . $type . '/');
            ImageService::manipulateImage(Constant::OPERATION_TYPE, $request->image, $this->imageFolder . '' . $type . '/', 150, 100, $fileName);

            $data['image']  = $fileName;
            $data['updated_at']  = date('Y-m-d h:i:s');
        }


        (new Common)->setTable($type)->where('id', $Id)->update($data);

        CommonTranslation::setGlobalTable($type . $this->translationTableName);
        $translations = [];
        foreach ($request->name as $key => $value) {
            $translations[] = [
                'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                $type . '_id' => $Id,
                'language_id' => $key,
                'name' => $value,
            ];
        }
        (new CommonTranslation)->setTable($type . $this->translationTableName)::upsert($translations, ['id']);

        return redirect($this->dropdownUrl . $type)
            ->with('success', ucwords(__($this->dropdownName . str_replace("_", " ", $type))) . trans('messages.recordUpdatedSpecific'));
    }

    public function status($id, $type, $value)
    {

        (new Common)->setTable($type)->where('id', $id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);
        return redirect($this->dropdownUrl . $type)
            ->with('success', ucwords(__($this->dropdownName . str_replace("_", " ", $type))) . trans('messages.statusUpdatedSpecific'));
    }
    public function delete($id, $type)
    {


        $image = (new Common)->setTable($type)->where('id', $id)->value('image');
        if ($image != null) {
            $storage = Storage::disk(env('FILESYSTEM_DISK'));
            $storage->delete([$this->imageFolder . '' . $type . '/' . $image]);
            $storage->delete([$this->imageFolder . '' . $type . '/' . 'thumb_' . $image]);
        }

        CommonTranslation::setGlobalTable($type . $this->translationTableName);
        $standout = (new Common)->setTable($type)->where('id', $id)->first();
        $standout->delete();

        return redirect('/admin/dropdown/index/' . $type)
            ->with('success', ucwords(__($this->dropdownName . str_replace("_", " ", $type))) . trans('messages.recordDeletedSpecific'));
    }
}
