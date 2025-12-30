<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\PortfolioRequest as FormRequest;
use App\Models\Portfolio as MainModel;
use App\Models\PortfolioTranslation as TranslationModel;
use App\Services\ImageService;
use App\Constants\Constant;
use App\Models\Portfolio;
use App\Services\CommonService;
use App\Services\FilterService;
use App\Services\GeneralService;
use App\Services\MultiLangActivityService;
use  DB, Config, Session, Redirect;

class PortfolioController extends BaseController
{

    protected $imageFolderPortfolio;
    protected $listRoutePortfolio;
    protected $addRoutePortfolio;
    protected $editRoutePortfolio;
    protected $thumbPortfolio;
    protected $mainTablePortfolio;
    protected $foreignKeyPortfolio;
    protected $translationFieldsPortfolio;
    protected $hasManyRelationPortfolio;
    protected $mainTableSingularPortfolio;
    protected $titlePortfolio;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolderPortfolio = Config::get('constants.PORTFOLIO_FOLDER');
        $this->listRoutePortfolio = 'admin.portfolio.index';
        $this->addRoutePortfolio = 'admin.portfolio.add';
        $this->editRoutePortfolio = 'admin.portfolio.edit';
        $this->thumbPortfolio  = '/thumb-';
        $this->mainTablePortfolio  = 'portfolios';
        $this->mainTableSingularPortfolio  = 'portfolio';
        $this->foreignKeyPortfolio  = 'portfolio_id';
        $this->translationFieldsPortfolio  = ['portfolios.*', 'portfolio_translations.description', 'portfolio_translations.title', 'portfolio_translations.url'];
        $this->hasManyRelationPortfolio = 'portfolio_translations';
        $this->titlePortfolio  = 'Portfolio';

        \View::share([
            'imageFolder' => $this->imageFolderPortfolio,
            'title' =>  $this->titlePortfolio,
            'listRoute' => $this->listRoutePortfolio,
            'addRoute' => $this->addRoutePortfolio,
            'saveRoute' => "admin.portfolio.save",
            'updateRoute' => "admin.portfolio.update",
            'listUrl' => "portfolio-list",
            'formId' => "portfolioForm",
            'mainTable' => $this->mainTablePortfolio,
            'formPath' => 'admin.portfolio.form',
            'addPermission' => "PORTFOLIO_MANGER_ADD",
            'editPermission' => "PORTFOLIO_MANGER_EDIT",
            'deletePermission' => "PORTFOLIO_MANGER_DELETE",
            'statusPermission' => "PORTFOLIO_MANGER_CHANGE_STATUS"
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {
        $DB                         =   MainModel::query();
        $fieldsToSearch             =   array('*description-title*' => '*like-like*', 'status' => '=');
        $searchVariable             =    $request->all();
        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $this->mainTablePortfolio,
                "foreignKey" => $this->foreignKeyPortfolio,
                "translationFields" => $this->translationFieldsPortfolio,
                "mainTableSingular" => $this->mainTableSingularPortfolio
            ]
        );

        extract($output);
        $statusChangeUrl        =    'admin/portfolio-list/status/';
        return  View($this->listRoutePortfolio, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string', 'statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        return  View($this->addRoutePortfolio);
    }

    /**
     * Save Added Record
     */
    public function save(FormRequest $request)
    {
        $error = '';

        DB::beginTransaction();
        try {
            $fileName = '';
            if (!empty($request->image)) {
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolderPortfolio);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderPortfolio,
                    150,
                    100,
                    $fileName
                );
            }
            $row = MainModel::create([
                'image'        => $fileName
            ]);

            $translations = [];
            foreach ($request->description as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'description' => $value,
                    'title' => $request->title[$key],
                    'url' => $request->url[$key],
                ];
            }
            $row->{$this->hasManyRelationPortfolio}()->createMany($translations);

        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();

        Session::flash('success', $this->titlePortfolio . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRoutePortfolio);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $DB   = new MainModel();
        $data = $DB::with($this->hasManyRelationPortfolio)->where('id', $id)->first();
        if (!$data) {
            return abort(403);
        }
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['thumbImage'] = $data->thumbImage;
        if (!empty($data[$this->hasManyRelationPortfolio])) {
            foreach ($data[$this->hasManyRelationPortfolio] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return  View($this->editRoutePortfolio)->with(['data' => $dataReArrange]);
    }

    /**
     * Save Edited Record
     */

    public function update($id, FormRequest $request)
    {
        $error = "";
        DB::beginTransaction();

        try {
            $row = MainModel::findOrFail($id);
            if (!empty($request->image)) {
                $fileName = ImageService::fileUploadImage($request->image, $row->image, $this->imageFolderPortfolio);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderPortfolio,
                    150,
                    100,
                    $fileName
                );
                $row->image = $fileName;
            }
            $row->updated_at = date("Y-m-d H:i:s");

            if ($row->save()) {
                $translations = [];
                foreach ($request->description as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyPortfolio => $row->id,
                        'language_id' => $key,
                        'description' => $value,
                        'title' => $request->title[$key],
                        'url' => $request->url[$key],
                    ];
                }
                TranslationModel::upsert($translations, ['id']);
            }
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }

        if ($error !== "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }

        DB::commit();
        Session::flash('success', $this->titlePortfolio . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRoutePortfolio);
    }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $portfolio = new Portfolio();
        $attributes = $portfolio->getTranslationAttributes();
        $languages_ids = $row->portfolio_translations()->pluck('language_id');
        $translations = $row->portfolio_translation()->whereIn('language_id', $languages_ids)->get()->keyBy('language_id');
        MultiLangActivityService::DeleteActivity(model:$row,languages_ids:$languages_ids,translations:$translations,attributes:$attributes);

        $row->delete();
        return redirect()->route($this->listRoutePortfolio)
            ->with('success', $this->titlePortfolio . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        MainModel::find($id)->update(['status' => $value]);
        return CommonService::redirectStatusChange(redirect()->back()->with('success', $this->titlePortfolio . __('messages.statusUpdatedSpecific')));
    }
}
