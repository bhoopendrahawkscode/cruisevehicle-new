<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use App\Http\Requests\VideoRequest as FormRequest;
use App\Models\Video as MainModel;
use App\Models\VideoTranslation as TranslationModel;
use App\Services\CommonService;
use App\Services\FilterService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Models\VideoToken;
use App\Models\Attachment;
class VideoController extends BaseController
{
    protected $listRouteVideo;
    protected $addRouteVideo;
    protected $editRouteVideo;
    protected $viewRouteVideo;
    protected $mainTableVideo;
    protected $foreignKeyVideo;
    protected $translationFieldsVideo;
    protected $hasManyRelationVideo;
    protected $mainTableSingularVideo;
    protected $titleVideo;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRouteVideo = 'admin.video.index';
        $this->addRouteVideo = 'admin.video.add';
        $this->editRouteVideo = 'admin.video.edit';
        $this->viewRouteVideo = 'admin.video.view';
        $this->thumbVideo  = '/thumb-';
        $this->mainTableVideo  = 'videos';
        $this->mainTableSingularVideo  = 'video';
        $this->foreignKeyVideo  = 'video_id';
        $this->translationFieldsVideo  = ['videos.*', 'video_translations.name','video_translations.artist'];
        $this->hasManyRelationVideo = 'video_translations';
        $this->titleVideo  = __('messages.video');

        View::share([
            'title' =>  $this->titleVideo,
            'listRoute' => $this->listRouteVideo,
            'addRoute' => $this->addRouteVideo,
            'saveRoute' => "admin.video.save",
            'updateRoute' => "admin.video.update",
            'listUrl' => "video-list",
            'formId' => "videoForm",
            'mainTable' => $this->mainTableVideo,
            'formPath' => 'admin.video.form',
            'addPermission' => "VIDEOS_MANAGEMENT_ADD",
            'editPermission' => "VIDEOS_MANAGEMENT_EDIT",
            'viewPermission' => "VIDEOS_MANAGEMENT_VIEW",
            'deletePermission' => "VIDEOS_MANAGEMENT_DELETE",
            'statusPermission' => "VIDEOS_MANAGEMENT_CHANGE_STATUS",
            'npmServer' => env('NPM_SERVER'),
            'section' =>   __('messages.videosManagement'),
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
                "mainTable" => $this->mainTableVideo,
                "foreignKey" => $this->foreignKeyVideo,
                "translationFields" => $this->translationFieldsVideo,
                "mainTableSingular" => $this->mainTableSingularVideo
            ]
        );
        extract($output);
        $statusChangeUrl        =    'admin/video-list/status/';
        return  View($this->listRouteVideo, compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    /**
     * Add Record
     */
    public function add(Request $request)
    {
        if(empty($request->token)){
            return Redirect::route($this->listRouteVideo);
        }
        $tokenDetails = VideoToken::getVideoTokenByToken($request->token);
        if($tokenDetails == null){
            $token = new VideoToken;
            $token->token = $request->token;
            $token->save();
        }
        $tokenDetails = VideoToken::getVideoTokenByToken($request->token);
        return  View($this->addRouteVideo)->with(['tokenDetails' => $tokenDetails]);

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
                'duration'        => $request->duration,
                'video'           => $request->video,
                'category'        => $request->category
            ]);
            VideoToken::where('url',$request->video)->delete();
            Attachment::deleteFile($request->video);
            $translations = [];
            foreach ($request->name as $key => $value) {
                $translations[] = [
                    'language_id' => $key,
                    'name' => $value,
                    'artist' => $request->artist[$key],
                ];
            }
            $row->{$this->hasManyRelationVideo}()->createMany($translations);
        } catch (\Throwable $e) {
            $error = CommonService::getExceptionError($e);
        }
        if ($error != "") {
            DB::rollback();
            DB::commit();
            return CommonService::redirectBackWithError($error);
        }
        DB::commit();
        Session::flash('success', $this->titleVideo . __("messages.recordAddedSpecific"));
        return Redirect::route($this->listRouteVideo);
    }

    /**
     * Edit Record
     */

    public function edit(Request $request,$id)
    {
        if(empty($request->token)){
            return Redirect::route($this->listRouteVideo);
        }
        $dataReArrange      =       $this->getEditView($id);
        VideoToken::where('token', $request->token)->delete();
        $token = new VideoToken;
        $token->token = $request->token;
        $token->url = $dataReArrange['video'];
        $token->save();
        return  View($this->editRouteVideo)->with(['data' => $dataReArrange,'tokenDetails' => $token]);
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
            $row->duration      =   $request->duration;
            $row->video         =   $request->video;
            $row->category      =   $request->category;
            $row->updated_at    =   date("Y-m-d H:i:s");
            if ($row->save()) {
                VideoToken::where('url',$request->video)->delete();
                Attachment::deleteFile($request->video);
                $translations = [];
                foreach ($request->name as $key => $value) {
                    $translations[] = [
                        'id' => (isset($request->id[$key])) ? $request->id[$key] : null,
                        $this->foreignKeyVideo => $row->id,
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
        Session::flash('success', $this->titleVideo . __("messages.recordUpdatedSpecific"));
        return Redirect::route($this->listRouteVideo);
    }

    /**
     * View Record
     */

     public function view($id)
     {
        $dataReArrange      =       $this->getEditView($id);
        return  View($this->viewRouteVideo)->with(['data' => $dataReArrange]);
     }


    /**
     * Delete Record
     */
    public function delete($id)
    {
        $row =  MainModel::where('id', $id)->first();
        $row->delete();
        return redirect()->route($this->listRouteVideo)
            ->with('success', $this->titleVideo . __("messages.recordDeletedSpecific"));
    }

    /*
	* Change Record Status
	*/
    public function status($id, $value)
    {
        MainModel::find($id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);

        Session::flash('success',$this->titleVideo . __('messages.statusUpdatedSpecific'));
        return CommonService::redirectStatusChange(Redirect::back());

    }
    public function getEditView($id){
        $db             = new MainModel();
        $dataVideo           = $db::with($this->hasManyRelationVideo)->where('id', $id)->first();

        if (!$dataVideo) {
            return abort(403);
        }
        $dataReArrangeVideo['recordId'] = $dataVideo->id;
        $dataReArrangeVideo['duration'] = $dataVideo->duration;
        $dataReArrangeVideo['video'] = $dataVideo->video;
        $dataReArrangeVideo['category'] = $dataVideo->category;
        if (!empty($dataVideo[$this->hasManyRelationVideo])) {
            foreach ($dataVideo[$this->hasManyRelationVideo] as $translationRow) {
                foreach ($translationRow->getAttributes() as $property => $propertyValue) {
                    $dataReArrangeVideo[$property][$translationRow->language_id]      =  $propertyValue;
                }
            }
        }
        return $dataReArrangeVideo;
    }

    public function updateToken(Request $request){
        $tokenDetails = VideoToken::getVideoTokenByToken($request->token);
        if(empty($tokenDetails)){
            $tokenDetails   =   new VideoToken;
            $tokenDetails->token =  $request->token;
        }
        $tokenDetails->url  = $request->url;
        $tokenDetails->save();

        if (strpos($request->token, 'e_') !== false) {
            $request->token = str_replace("e_", "", $request->token);
            MainModel::where('id',$request->token)->update(['video' => $request->url]);
            VideoToken::where('token','e_'.$request->token)->delete();
            Attachment::deleteFile($request->url);
        }
        echo 'updated';die;
    }
}
