<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\FaqRequest as FormRequest;
use App\Models\Faq as MainModel;
use App\Models\FaqTranslation as TranslationModel;
use App\Services\ImageService;
use App\Models\FaqCategory;
use App\Models\FAqCategoryTranslation;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\FilterService;
use  DB, Config, Session, Redirect;

class FaqController extends BaseController
{
    protected $imageFolderFaq;
    protected $listRouteFaq;
    protected $addRouteFaq;
    protected $editRouteFaq;
    protected $thumbFaq;
    protected $mainTableFaq;
    protected $foreignKeyFaq;
    protected $translationFieldsFaq;
    protected $hasManyRelationFaq;
    protected $mainTableSingularFaq;
    protected $titleFaq;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolderFaq = Config::get('constants.FAQ_FOLDER');
        $this->listRouteFaq = 'admin.faq.index';
        $this->addRouteFaq = 'admin.faq.add';
        $this->editRouteFaq = 'admin.faq.edit';
        $this->thumbFaq  = '/thumb-';
        $this->mainTableFaq  = 'faqs';
        $this->mainTableSingularFaq  = 'faq';
        $this->foreignKeyFaq  = 'faq_id';
        $this->translationFieldsFaq  = ['faqs.*', 'faq_translations.question', 'faq_translations.answer'];
        $this->hasManyRelationFaq = 'faq_translations';
        $this->titleFaq  = __('messages.faq');

        $methodsToLoadData          =   ['add','save','edit','update'];
        $faqCategoriesData          =   [];
        if (in_array($request->route()->getActionMethod(),$methodsToLoadData)) {
            $where                  =   [];
            if (in_array($request->route()->getActionMethod(),['add','save'])) {
                $where                  = [['faqcategories.status',"=",1]];
            }
            $faqCategories = FaqCategory::with(['faq_category_translation'=> function ($query) {
                $query->orderBy('name');
            }])->where($where)->get()->toArray();
            $faqCategoriesData = [];
            foreach ($faqCategories as  $value) {
                if (!empty($value['faq_category_translation'])) {
                    $faqCategoriesData[$value['faq_category_translation']['faqcategories_id']] =
                    $value['faq_category_translation']['name'];
                }
            }
            $faqCategoriesData = ['' => __('messages.selectFaqCategory')] + $faqCategoriesData;
        }
        \View::share([
            'imageFolder' =>  $this->imageFolderFaq,
            'title' => $this->titleFaq,
            'listRoute' => $this->listRouteFaq,
            'addRoute' => $this->addRouteFaq,
            'saveRoute' => "admin.faq.save",
            'updateRoute' => "admin.faq.update",
            'listUrl' => "faq-list",
            'formId' => "faqForm",
            'mainTable' =>  $this->mainTableFaq,
            'formPath' => 'admin.faq.form',
            'addPermission' => "FAQ_ADD",
            'editPermission' => "FAQ_EDIT",
            'deletePermission' => "FAQ_DELETE",
            'statusPermission' => "FAQ_CHANGE_STATUS",
            'faqCategoriesData' =>  $faqCategoriesData
        ]);
    }

    /**
     * List Records
     */
    public function index(Request $request)
    {


        $DB                         =   MainModel::query()->with('faq_category');
        $fieldsToSearch              =   array('*question*' => '*like-like*', 'status' => '=');
        $searchVariable                =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
                "mainTable" => $this->mainTableFaq,
                "foreignKey" => $this->foreignKeyFaq,
                "translationFields" => $this->translationFieldsFaq,
                "mainTableSingular" => $this->mainTableSingularFaq
            ]
        );

        extract($output);
        $statusChangeUrl        =    'admin/faq-list/status/';
        return  View($this->listRouteFaq, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        return  View($this->addRouteFaq);
    }

    /**
     * Save Added Record
     */
    public function save(FormRequest $request)
    {


        $error = '';
        DB::beginTransaction();
        try {


            $row = MainModel::create([]);
            $translations = [];

            foreach ($request->question as $key => $question) {
                $translations[] = [
                    'language_id' => $key,
                    'question' => $question,
                    'answer' => $request->answer[$key]
                ];
            }
            $row->{$this->hasManyRelationFaq}()->createMany($translations);
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        Session::flash('success', $this->titleFaq . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteFaq);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $DB             = new MainModel();
        $data = $DB::with($this->hasManyRelationFaq)->where('id', $id)->first();
        if (!$data) {
            return abort(403);
        }
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['thumbImage'] = $data->thumbImage;
        $dataReArrange['faq_category_id'] = $data->faq_category_id;
        if (!empty($data[$this->hasManyRelationFaq])) {
            foreach ($data[$this->hasManyRelationFaq] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return  View($this->editRouteFaq)->with(['data' => $dataReArrange]);
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

            $row->updated_at = date("Y-m-d H:i:s");

            if ($row->save()) {
                $translations = [];
                foreach ($request->question as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyFaq => $row->id,
                        'language_id' => $key,
                        'question' => $value,
                        'answer' => $request->answer[$key],
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
        Session::flash('success', $this->titleFaq . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteFaq);
    }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        return redirect()->route($this->listRouteFaq)
            ->with('success', $this->titleFaq .  __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        $DB = new MainModel();
        $DB->where('id', $id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);
        Session::flash('success',$this->titleFaq . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());
    }
}
