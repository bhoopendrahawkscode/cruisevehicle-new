<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\QuestionRequest as FormRequest;
use App\Models\Question as MainModel;
use App\Models\QuestionTranslation as TranslationModel;
use App\Services\CommonService;
use App\Services\FilterService;
use App\Models\QuestionHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
class QuestionController extends BaseController
{

    protected $listRouteQuestion;
    protected $addRouteQuestion;
    protected $editRouteQuestion;
    protected $viewRouteQuestion;
    protected $thumbQuestion;
    protected $mainTableQuestion;
    protected $foreignKeyQuestion;
    protected $translationFieldsQuestion;
    protected $hasManyRelationQuestion;
    protected $mainTableSingularQuestion;
    protected $titleQuestion;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRouteQuestion = 'admin.question.index';
        $this->addRouteQuestion = 'admin.question.add';
        $this->editRouteQuestion = 'admin.question.edit';
        $this->viewRouteQuestion = 'admin.question.view';
        $this->thumbQuestion  = '/thumb-';
        $this->mainTableQuestion  = 'questions';
        $this->mainTableSingularQuestion  = 'question';
        $this->foreignKeyQuestion  = 'question_id';
        $this->translationFieldsQuestion  = ['questions.*', 'question_translations.name'];
        $this->hasManyRelationQuestion = 'question_translations';
        $this->titleQuestion  = __('messages.moodQuestion');
        View::share([
            'title' =>  $this->titleQuestion,
            'listRoute' => $this->listRouteQuestion,
            'addRoute' => $this->addRouteQuestion,
            'saveRoute' => "admin.question.save",
            'updateRoute' => "admin.question.update",
            'listUrl' => "question-list",
            'formId' => "questionForm",
            'mainTable' => $this->mainTableQuestion,
            'formPath' => 'admin.question.form',
            'addPermission' => "QUESTIONS_MANAGEMENT_ADD",
            'editPermission' => "QUESTIONS_MANAGEMENT_EDIT",
            'viewPermission' => "QUESTIONS_MANAGEMENT_VIEW",
            'deletePermission' => "QUESTIONS_MANAGEMENT_DELETE",
            'statusPermission' => "QUESTIONS_MANAGEMENT_CHANGE_STATUS",
            'section' =>   __('messages.moodQuestionsManagement'),
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {

        $db                         =   MainModel::query();
        $fieldsToSearch             =   array('name' => 'like', 'status' => '=');
        $searchVariable             =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $db,
            'created_at',
            [
                "mainTable" => $this->mainTableQuestion,
                "foreignKey" => $this->foreignKeyQuestion,
                "translationFields" => $this->translationFieldsQuestion,
                "mainTableSingular" => $this->mainTableSingularQuestion
            ]
        );
        extract($output);
        $statusChangeUrl        =    'admin/question-list/status/';
        return  View($this->listRouteQuestion, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add()
    {
        return  View($this->addRouteQuestion);
    }

    /**
     * Save Added Record
     */
    public function save(FormRequest $request)
    {


        $error = '';
        DB::beginTransaction();
        try {


            $row = MainModel::create([
            ]);
            $translations = [];
            foreach ($request->name as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'name' => $value,
                ];
            }
            $row->{$this->hasManyRelationQuestion}()->createMany($translations);
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        Session::flash('success', $this->titleQuestion . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteQuestion);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {

        return abort(403); // developer removed this feature
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
                foreach ($request->name as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyQuestion => $row->id,
                        'language_id' => $key,
                        'name' => $value,
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
        Session::flash('success', $this->titleQuestion . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteQuestion);
    }

    /**
     * View Record
     */

     public function view($id)
     {
        $dataReArrange      =       $this->getEditView($id);

        return  View($this->viewRouteQuestion)->with(['data' => $dataReArrange]);
     }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        QuestionHistory::where('question_id', $id)->delete();
        return redirect()->route($this->listRouteQuestion)
            ->with('success', $this->titleQuestion . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        $db = new MainModel();
        $db->where('id', $id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);

        Session::flash('success',$this->titleQuestion . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());

    }

    public function getEditView($id){
        $db             = new MainModel();
        $dataQuestion = $db::with($this->hasManyRelationQuestion)->where('id', $id)->first();

        if (!$dataQuestion) {
            return abort(403);
        }
        $dataQuestionReArrange['recordId'] = $dataQuestion->id;
        if (!empty($dataQuestion[$this->hasManyRelationQuestion])) {
            foreach ($dataQuestion[$this->hasManyRelationQuestion] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataQuestionReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return $dataQuestionReArrange;
    }
}
