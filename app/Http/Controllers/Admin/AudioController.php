<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\AudioRequest as FormRequest;
use App\Models\Audio as MainModel;
use App\Models\AudioTranslation as TranslationModel;
use App\Services\CommonService;
use App\Services\FilterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Constants\Constant;
use App\Models\AudioToken;
use App\Models\Attachment;
use App\Models\AudioHistory;
use Config;
use App\Services\ImageService;
class AudioController extends BaseController
{
    protected $imageFolderAudio;
    protected $listRouteAudio;
    protected $addRouteAudio;
    protected $editRouteAudio;
    protected $viewRouteAudio;
    protected $thumbAudio;
    protected $mainTableAudio;
    protected $foreignKeyAudio;
    protected $translationFieldsAudio;
    protected $hasManyRelationAudio;
    protected $mainTableSingularAudio;
    protected $titleAudio;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->imageFolderAudio = Config::get('constants.AUDIO_FOLDER');
        $this->listRouteAudio = 'admin.audio.index';
        $this->addRouteAudio = 'admin.audio.add';
        $this->editRouteAudio = 'admin.audio.edit';
        $this->viewRouteAudio = 'admin.audio.view';
        $this->thumbAudio  = '/thumb-';
        $this->mainTableAudio  = 'audios';
        $this->mainTableSingularAudio  = 'audio';
        $this->foreignKeyAudio  = 'audio_id';
        $this->translationFieldsAudio  = ['audios.*', 'audio_translations.name','audio_translations.artist'];
        $this->hasManyRelationAudio = 'audio_translations';
        $this->titleAudio  = __('messages.audio');

        View::share([
            'title' =>  $this->titleAudio,
            'listRoute' => $this->listRouteAudio,
            'addRoute' => $this->addRouteAudio,
            'saveRoute' => "admin.audio.save",
            'updateRoute' => "admin.audio.update",
            'listUrl' => "audio-list",
            'formId' => "audioForm",
            'mainTable' => $this->mainTableAudio,
            'formPath' => 'admin.audio.form',
            'addPermission' => "AUDIOS_MANAGEMENT_ADD",
            'editPermission' => "AUDIOS_MANAGEMENT_EDIT",
            'viewPermission' => "AUDIOS_MANAGEMENT_VIEW",
            'deletePermission' => "AUDIOS_MANAGEMENT_DELETE",
            'statusPermission' => "AUDIOS_MANAGEMENT_CHANGE_STATUS",
            'npmServer' => env('NPM_SERVER'),
            'section' =>   __('messages.audiosManagement'),
        ]);
    }
    /**
     * List Records
     */
    public function index(Request $request)
    {

        $db                         =   MainModel::query();
        $fieldsToSearch             =   array('*artist*' => '*like*', 'status' => '=');
        $searchVariable             =    $request->all();

        $output                     =    FilterService::getFiltersLanguage(
            new Input,
            $request,
            $searchVariable,
            $fieldsToSearch,
            $db,
            'created_at',
            [
                "mainTable" => $this->mainTableAudio,
                "foreignKey" => $this->foreignKeyAudio,
                "translationFields" => $this->translationFieldsAudio,
                "mainTableSingular" => $this->mainTableSingularAudio
            ]
        );
        extract($output);
        $statusChangeUrl        =    'admin/audio-list/status/';
        $allAudios              =     [];
        if(!empty($result)){
           foreach($result as $row){
                $allAudios[$row->id]  = $row->id;
           }
        }
        $audioHistoryCounts   =  AudioHistory::getHistoryCountsByIds($allAudios);
        return  View($this->listRouteAudio, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl','audioHistoryCounts'));
    }

    /**
     * Add Record
     */
    public function add(Request $request)
    {
        if(empty($request->token)){
            return Redirect::route($this->listRouteAudio);
        }
        $tokenDetails = AudioToken::getAudioTokenByToken($request->token);
        if($tokenDetails == null){
            $token = new AudioToken;
            $token->token = $request->token;
            $token->save();
        }
        $tokenDetails = AudioToken::getAudioTokenByToken($request->token);
        return  View($this->addRouteAudio)->with(['tokenDetails' => $tokenDetails]);

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
                $fileName  = ImageService::fileUploadImage($request->image, '', $this->imageFolderAudio);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderAudio,
                    150,
                    100,
                    $fileName
                );
            }

            $row = MainModel::create([
                'duration'        => $request->duration,
                'audio'           => $request->audio,
                'image'           => $fileName,
                'category'        => $request->category
            ]);
            AudioToken::where('url',$request->audio)->delete();
            Attachment::deleteFile($request->audio);
            $translations = [];
            foreach ($request->name as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'name' => $value,
                    'artist' => $request->artist[$key],
                ];
            }
            $row->{$this->hasManyRelationAudio}()->createMany($translations);
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        Session::flash('success', $this->titleAudio . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteAudio);
    }

    /**
     * Edit Record
     */

    public function edit(Request $request,$id)
    {
        if(empty($request->token)){
            return Redirect::route($this->listRouteAudio);
        }
        $dataReArrange      =       $this->getEditView($id);
        AudioToken::where('token', $request->token)->delete();
        $token = new AudioToken;
        $token->token = $request->token;
        $token->url = $dataReArrange['audio'];
        $token->save();
        return  View($this->editRouteAudio)->with(['data' => $dataReArrange,'tokenDetails' => $token]);
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
                $fileName = ImageService::fileUploadImage($request->image, $row->image, $this->imageFolderAudio);
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    $this->imageFolderAudio,
                    200,
                    200,
                    $fileName
                );
                $row->image = $fileName;
            }
            $row->duration      =   $request->duration;
            $row->audio         =   $request->audio;
            $row->category      =   $request->category;
            $row->updated_at    =   date("Y-m-d H:i:s");
            if ($row->save()) {
                AudioToken::where('url',$request->audio)->delete();
                Attachment::deleteFile($request->audio);
                $translations = [];
                foreach ($request->name as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyAudio => $row->id,
                        'language_id' => $key,
                        'name' => $value,
                        'artist' => $request->artist[$key],
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
        Session::flash('success', $this->titleAudio . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteAudio);
    }

    /**
     * View Record
     */

     public function view($id)
     {
        $dataReArrange      =       $this->getEditView($id);
        return  View($this->viewRouteAudio)->with(['data' => $dataReArrange]);
     }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        return redirect()->route($this->listRouteAudio)
            ->with('success', $this->titleAudio . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        MainModel::find($id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);

        Session::flash('success',$this->titleAudio . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());

    }
    public function getEditView($id){
        $db                  =   new MainModel();
        $dataAudio           =  $db::with($this->hasManyRelationAudio)->where('id', $id)->first();

        if (!$dataAudio) {
            return abort(403);
        }
        $dataReArrangeAudio['recordId'] = $dataAudio->id;
        $dataReArrangeAudio['duration'] = $dataAudio->duration;
        $dataReArrangeAudio['audio'] = $dataAudio->audio;
        $dataReArrangeAudio['artist'] = $dataAudio->artist;
        $dataReArrangeAudio['thumbImage'] = $dataAudio->thumbImage;
        $dataReArrangeAudio['category'] = $dataAudio->category;
        if (!empty($dataAudio[$this->hasManyRelationAudio])) {
            foreach ($dataAudio[$this->hasManyRelationAudio] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrangeAudio[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return $dataReArrangeAudio;
    }

    public function updateToken(Request $request){
        $tokenDetails = AudioToken::getAudioTokenByToken($request->token);
        if(empty($tokenDetails)){
            $tokenDetails   =   new AudioToken;
            $tokenDetails->token =  $request->token;
        }
        $tokenDetails->url  = $request->url;
        $tokenDetails->save();

        if (strpos($request->token, 'e_') !== false) {
            $request->token = str_replace("e_", "", $request->token);
            MainModel::where('id',$request->token)->update(['audio' => $request->url]);
            AudioToken::where('token','e_'.$request->token)->delete();
            Attachment::deleteFile($request->url);
        }
        echo 'updated';die;
    }
}
