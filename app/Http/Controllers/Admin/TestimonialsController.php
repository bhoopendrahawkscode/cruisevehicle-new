<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\TestimonialRequest as FormRequest;
use App\Models\Testimonial as MainModel;
use App\Models\TestimonialTranslation as TranslationModel;
use App\Services\ImageService;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\FilterService;
use  DB, Config, Session, Redirect;

class TestimonialsController extends BaseController
{

    protected $imageFolderTestimonial;
    protected $listRouteTestimonial;
    protected $addRouteTestimonial;
    protected $editRouteTestimonial;
    protected $thumbTestimonial;
    protected $mainTableTestimonial;
    protected $foreignKeyTestimonial;
    protected $translationFieldsTestimonial;
    protected $hasManyRelationTestimonial;
    protected $mainTableSingularTestimonial;
    protected $titleTestimonial;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolderTestimonial = Config::get('constants.TESTIMONIALS_FOLDER');
        $this->listRouteTestimonial = 'admin.testimonials.index';
        $this->addRouteTestimonial = 'admin.testimonials.add';
        $this->editRouteTestimonial = 'admin.testimonials.edit';
        $this->thumbTestimonial  = '/thumb-';
        $this->mainTableTestimonial  = 'testimonials';
        $this->mainTableSingularTestimonial  = 'testimonial';
        $this->foreignKeyTestimonial  = 'testimonial_id';
        $this->translationFieldsTestimonial  = ['testimonials.*', 'testimonial_translations.description', 'testimonial_translations.designation', 'testimonial_translations.giver'];
        $this->hasManyRelationTestimonial = 'testimonial_translations';
        $this->titleTestimonial  = __('messages.testimonial');

        \View::share([
            'imageFolder' => $this->imageFolderTestimonial,
            'title' =>  $this->titleTestimonial,
            'listRoute' => $this->listRouteTestimonial,
            'addRoute' => $this->addRouteTestimonial,
            'saveRoute' => "admin.testimonials.save",
            'updateRoute' => "admin.testimonial.update",
            'listUrl' => "testimonial-list",
            'formId' => "testimonialForm",
            'mainTable' => $this->mainTableTestimonial,
            'formPath' => 'admin.testimonials.form',
            'addPermission' => "TESTIMONIALS_MANGER_ADD",
            'editPermission' => "TESTIMONIALS_MANGER_EDIT",
            'deletePermission' => "TESTIMONIALS_MANGER_DELETE",
            'statusPermission' => "TESTIMONIALS_MANGER_CHANGE_STATUS"
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {

        $DB                         =   MainModel::query();
        $fieldsToSearch             =   array('*description-giver*' => '*like-like*', 'status' => '=');
        $searchVariable             =    $request->all();
        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $this->mainTableTestimonial,
                "foreignKey" => $this->foreignKeyTestimonial,
                "translationFields" => $this->translationFieldsTestimonial,
                "mainTableSingular" => $this->mainTableSingularTestimonial
            ]
        );

        extract($output);
        $statusChangeUrl        =    'admin/testimonial-list/status/';
        return  View($this->listRouteTestimonial, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string', 'statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        return  View($this->addRouteTestimonial);
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
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolderTestimonial);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderTestimonial,
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
                    'designation' => $request->designation[$key],
                    'giver' => $request->giver[$key],
                ];
            }
            $row->{$this->hasManyRelationTestimonial}()->createMany($translations);

        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        
        Session::flash('success', $this->titleTestimonial . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteTestimonial);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $DB   = new MainModel();
        $data = $DB::with($this->hasManyRelationTestimonial)->where('id', $id)->first();
        if (!$data) {
            return abort(403);
        }
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['thumbImage'] = $data->thumbImage;
        if (!empty($data[$this->hasManyRelationTestimonial])) {
            foreach ($data[$this->hasManyRelationTestimonial] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return  View($this->editRouteTestimonial)->with(['data' => $dataReArrange]);
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
                $fileName = ImageService::fileUploadImage($request->image, $row->image, $this->imageFolderTestimonial);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderTestimonial,
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
                        $this->foreignKeyTestimonial => $row->id,
                        'language_id' => $key,
                        'description' => $value,
                        'designation' => $request->designation[$key],
                        'giver' => $request->giver[$key],
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
        Session::flash('success', $this->titleTestimonial . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteTestimonial);
    }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        return redirect()->route($this->listRouteTestimonial)
            ->with('success', $this->titleTestimonial . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        MainModel::find($id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);
        Session::flash('success', $this->titleTestimonial . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());
    }
}
