<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\EmailTemplate;
use App\Models\EmailTemplateTranslation;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\EmailTemplateRequest;
use  DB,  Config,  Session, Redirect;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\FilterService;
class EmailTemplateController extends BaseController
{
    protected $imageFolderEmail;
    protected $listRouteEmail;
    protected $addRouteEmail;
    protected $editRouteEmail;
    protected $thumbEmail;
    protected $mainTableEmail;
    protected $foreignKeyEmail;
    protected $translationFieldsEmail;

    protected $titleEmail;

    public function __construct()
    {

        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRouteEmail = 'admin.email_template.index';
        $this->addRouteEmail = 'admin.email_template.add';
        $this->editRouteEmail = 'admin.email_template.edit';
        $this->thumbEmail  = '/thumb-';
        $this->mainTableEmail  = 'emailtemplates';
        $this->foreignKeyEmail  = 'emailtemplate_id';
        $this->translationFieldsEmail  = ['emailtemplates.*','emailtemplate_translations.name','emailtemplate_translations.subject'];
        $this->titleEmail       =   trans('messages.emailTemplate');

        \View::share([
            'title'=> $this->titleEmail,
            'listRoute'=> $this->listRouteEmail,
            'addRoute'=> $this->addRouteEmail,
            'saveRoute'=>"admin.email_template.save",
            'updateRoute'=> "admin.email_template.update",
            'listUrl'=> "email_template-list",
            'formId'=> "emailTemplateForm",
            'addPermission'=> "EMAIL_TEMPLATE_ADD",
            'editPermission'=> "EMAIL_TEMPLATE_MANGER_EDIT",
            'deletePermission'=> "EMAIL_TEMPLATE_DELETE",
            'statusPermission'=> "EMAIL_TEMPLATE_CHANGE_STATUS",
            'section' =>   __('messages.EmailTemplateManagement'),
        ]);

    }
    /**
     * List Records
     */
    public function index(Request $request)
    {


        $DB                         =   EmailTemplate::query();
        $fieldsToSearch             =   array('name' => 'like', 'status' => '=');
        $searchVariable             =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $DB,
            'updated_at',
            [
               "mainTable" => $this->mainTableEmail,
               "foreignKey" => $this->foreignKeyEmail,
               "translationFields" => $this->translationFieldsEmail
            ]
        );
        extract($output);


        return  View($this->listRouteEmail)->with(['result'=>$result,'searchVariable'=>$searchVariable,
        'sortBy'=>$sortBy,'order'=>$order,'query_string'=>$query_string,'mainTable'=>$this->mainTableEmail]);
    }

    /**
     * Edit Record
     */

    public function edit($id)
    {
        $DB             = new EmailTemplate();
        $data = $DB::with('emailtemplate_translations')->where('id', $id)->first();
        if (!$data) {
            return abort(403);
        }
        $dataReArrange['recordId'] = $data->id;
        $dataReArrange['global_header_footer'] = $data->global_header_footer;
        if (!empty($data['emailtemplate_translations'])) {
            foreach ($data['emailtemplate_translations'] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrange[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return  View($this->editRouteEmail)->with(['data'=>$dataReArrange]);
    }

    /**
     * Save Edited Record
     */

    public function update($id, EmailTemplateRequest $request)
    {
        $error = "";
        DB::beginTransaction();
        try {

            $emailTemplates = EmailTemplate::findOrFail($id);
            $emailTemplates['global_header_footer'] = $request->global_header_footer ? $request->global_header_footer:0;
            $emailTemplates['updated_at'] = date("Y-m-d H:i:s");
            if ($emailTemplates->save()) {
                $translations = $this->assignProperties($request,$emailTemplates);
                EmailTemplateTranslation::upsert($translations, ['id']);
            }
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }

        DB::commit();
        Session::flash('success', $this->titleEmail.__("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteEmail);
    }

    public function assignProperties($request,$emailTemplates){
        $translations = [];
        foreach ($request->name as $key=>$value) {
            $translations[] = [
                'id' =>(isset($request->id[$key]))?$request->id[$key]:null,
                'emailtemplate_id' =>$emailTemplates->id,
                'language_id' =>$key,
                'name' => $value,
                'subject' =>(isset($request->subject[$key]))?$request->subject[$key]:null,
                'email_body' =>(isset($request->email_body[$key]))?$request->email_body[$key]:null
            ];
        }
        return $translations;
    }

}
