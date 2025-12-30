<?php

namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\API\CommunityRequest;
use App\Http\Requests\API\CommunityActionRequest;
use App\Http\Requests\API\CommunityIdRequest;
use App\Http\Requests\API\CommunityReportRequest;
use App\Http\Requests\API\ArchiveCommunityRequest;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\GeneralService;
use App\Services\ImageService;
use App\Models\Community;
use App\Models\User;
use App\Models\CommunityUser;
use App\Models\CommunityReport;
use App\Http\Resources\CommunityResource;
use App\Http\Resources\CommunityUserResource;
use App\Jobs\SendPushNotificationJob;
use  Auth, DB;

class CommunityController extends BaseController
{
    protected $paginate;
    protected $paginateAll;
    protected $message;
    protected $data;
    protected $noRecord;

    protected $dataListed;
    public function __construct()
    {
        $this->paginate              =       GeneralService::getSettings('pageLimit');
        $this->paginateAll           =       1000;
        $this->data                  =       new \stdClass;
        $this->message               =       __(Constant::ERROR_OCCURRED);
        $this->noRecord              =      trans('api.record_not_found');
        $this->dataListed            =      trans('messages.GET_DATA');
        define('SUCCESS_MESSAGE', Constant::API_SUCCESS);

    }

    public function getCommunityTypes(){
        $types              =   [];
        $communityTypes     =   Constant::COMMUNITIES;
        unset($communityTypes[0]);
        foreach($communityTypes as $row){
            $types[]       =   ['name'=>$row];
        }
        $this->data     =   $types;
        return $this->sendResponse($this->data,
            $this->dataListed);
    }

    public function getReportReasons(){
        $types              =   [];
        $reportReasons     =   Constant::REPORT_REASON;
        foreach($reportReasons as $row){
            $types[]       =   ['name'=>$row];
        }
        $this->data     =   $types;
        return $this->sendResponse($this->data,
            $this->dataListed);
    }

    public function saveCommunity(CommunityRequest $request)
    {

        DB::beginTransaction();
        try {

            $userId                          =       Auth::user()->id;

            $communityData                   =       [];
            if($request->id != ''){
                $communityData               =       Community::where('id', $request->id)
                ->where('user_id', $userId)->first();
                if(empty($communityData)){
                    $this->message = 'Invalid Community';
                    return $this->sendError($this->message);
                }
            }else{
                $communityData                  =       new Community();
                $communityData['code']          =       GeneralService::generateRandomCode();
            }
            $this->appendFile($communityData,$request);
            $communityData['user_id']         =       $userId;
            $communityData['name']            =       $request->name;
            $typeUpdated                      =   false;
            if($communityData['type']  != $request->type && $communityData['type'] != ''){
                $typeUpdated        =   true;
                $communityTypes     =   Constant::COMMUNITIES;
                $notificationMessage =  "From ".$communityTypes[$communityData['type']]." to ".$communityTypes[$request['type']];
            }
            $communityData['type']            =       $request->type;
            if($request->question != ''){
                $communityData['question']        =       $request->question;
            }
            $communityData->save();
            if($typeUpdated){
                 $this->sendNotificationTypeUpdated($communityData,$notificationMessage);
            }
            DB::commit();
            return $this->sendResponse(new CommunityResource($communityData,[]),trans('api.COMMUNITY_SAVED'));

        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message = __(Constant::ERROR_OCCURRED);
            if(env('APP_DEBUG')){
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function sendNotificationTypeUpdated($communityData,$notificationMessage){
        $communityUserList =  CommunityUser::with('user')->where("community_id",$communityData->id)->get();
        if(!empty($communityUserList)){
            foreach($communityUserList as $row){
                $user   =  $row->user->toArray();
                $temp['type']            =      'CommunityTypeUpdated';
                $temp['user_id']         =      $user['id'];
                $temp['link_id']         =      $communityData->id;
                $temp['title']           =      "Community type updated.";
                $temp['description']     =      "Community (".$communityData['name'].") has been changed ".$notificationMessage.".";
                dispatch(new SendPushNotificationJob($user, $temp)); // send notification to joiner
            }
        }
    }
    public function appendFile($communityData,$request){
        $fileName = '';
        if (!empty($request->image)) {
            $fileName  = ImageService::fileUploadImage($request->image,$communityData->image,
            Constant::COMMUNITY_FOLDER);
            ImageService::manipulateImage(
                Constant::OPERATION_TYPE,
                $request->image,
                Constant::COMMUNITY_FOLDER,
                100,
                100,
                $fileName
            );
            $communityData['image']       =      $fileName;
            return $communityData;
        }
    }
    public function getCommunities(Request $request)
    {
        try {
            $query                  =       Community::query();

            if ($request->has('owner_id')  && $request->owner_id != '') {
                $query->where('user_id', $request->owner_id);
            }
            if ($request->has('type')  && $request->type != '') {
                $query->where('type', $request->type);
            }
            if ($request->has('name')  && $request->name != '') {
                $query->where('name', 'like', '%' . $request->name . '%');
            }
            if ($request->has('user_id')  && $request->user_id != '') {
                $alreadyJoined = CommunityUser::where(['user_id'=>$request->user_id,'status'=>2])->pluck('community_id','community_id')
                ->toArray();
                $query->whereIn('communities.id', $alreadyJoined);
            }
            if ($request->has('hideHidden')  && $request->hideHidden != '') {
                $query->whereIn('communities.type', [1,2]);
            }
            $query->where('status',1);
            // only accepted members
            $query->selectRaw('communities.*,(SELECT COUNT(*) FROM community_users WHERE community_users.status=2 and communities.id=community_users.community_id) AS totalPeople');

            $dataList   =     $query->orderBy('totalPeople', "desc")
                            ->paginate($this->paginate);
            return $this->sendResponse(CommunityResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function getCommunityRequests(Request $request)
    {
        try {
            $query                  =       CommunityUser::query();
            $query->with(['user','community']);
            $query->where(['community_id'=> $request->community_id,'status'=>1]) ->first();
            $dataList   =     $query->orderBy('created_at', "desc")
                            ->paginate($this->paginate);
            return $this->sendResponse(CommunityUserResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function communityAction(CommunityActionRequest $request)
    {

        DB::beginTransaction();
        try {

            if($request->type == 'JOIN_CODE'){
                $communityDetails = Community::where('code', $request->code) ->first();
            }else{
                $communityDetails = Community::find($request->community_id);
            }

            $userId                          =       Auth::user()->id;
            if (empty($communityDetails) || $communityDetails->user_id == $userId) {
                $this->message = 'Invalid Action';
                return $this->sendError($this->message);
            }

            $adminDetails                    =       User::getUserDetails(1);

            $temp['type']            =      'AdminNotification';
            $temp['user_id']         =      $adminDetails['id'];
            $alreadyJoined = CommunityUser::where(['community_id'=> $communityDetails->id,'user_id'=>$userId]) ->first();
            $message2  = SUCCESS_MESSAGE;
            if($request->type == 'JOIN' || $request->type == 'JOIN_CODE' && empty($alreadyJoined)){
                if($communityDetails->type == 2){ // if private then request will be pending.
                    $status = 1;
                    $message2        =   "Your request has been sent to community admin.";
                }else{
                    $status = 2; // otherwise approved.
                    $message2        =      "Community Joined Successfully.";
                }
                CommunityUser::create(['community_id'=> $communityDetails->id,'user_id'=>$userId,'status'=>$status,'answer'=>$request->answer,'question'=>$request->question]);

                // send notification to super admin


                $temp['title']          = "New user requested to join Community.";
                $temp['description']    = "New user requested to join Community (".$communityDetails['name'].").";
                dispatch(new SendPushNotificationJob($adminDetails, $temp));

                 // send notification to community admin


                $communityAdminDetails                    =       User::getUserDetails($communityDetails['user_id']);

                $temp['type']            =      'PrivateCommunityJoinRequest';
                $temp['user_id']         =      $communityAdminDetails['id'];
                $temp['link_id']         =      $communityDetails->id;
                $temp['title']          = "New user requested to join Community.";
                $temp['description']    = "New user requested to join Community (".$communityDetails['name'].").";

                dispatch(new SendPushNotificationJob($communityAdminDetails, $temp));


                // send notification to community joiner


                $communityJoinerDetails                    =       User::getUserDetails($userId);

                $temp['type']            =      'PrivateCommunityConfirmation';
                $temp['user_id']         =      $communityJoinerDetails['id'];
                $temp['link_id']         =      $communityDetails->id;
                $temp['title']           =      "Community Request sent";
                $temp['description']     =      "Your request has been sent to community (".$communityDetails['name'].") admin.";

                dispatch(new SendPushNotificationJob($communityAdminDetails, $temp));

            }elseif($request->type == 'LEAVE' && !empty($alreadyJoined)){
                CommunityUser::where(['community_id'=> $communityDetails->id,'user_id'=>$userId])->delete();
                $temp['title']          = "User left Community";
                $temp['description']    = "An user left to join Community(".$communityDetails['name'].")";
                dispatch(new SendPushNotificationJob($adminDetails, $temp));
                $message2        =      "You Left Community Successfully.";
            }
            DB::commit();
            return $this->sendResponse($this->data,$message2);
        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message = __(Constant::ERROR_OCCURRED);
            if(env('APP_DEBUG')){
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function checkForInvalidCommunity($communityDetails){
        $userId                         =       Auth::user()->id;
        if (empty($communityDetails) || $communityDetails->user_id != $userId) {
           return 'Invalid Action.';
        }else{
            return '';
        }
    }
    public function updateCommunityStatus(CommunityIdRequest $request)
    {

        DB::beginTransaction();
        try {


            $communityDetails               =       Community::where('id', $request->community_id)->first();

            $message2 = $this->checkForInvalidCommunity($communityDetails);
            if($message2 !=''){
                return $this->sendError($message2);
            }
            if ($request->has('type')  && $request->type == "ACCEPT") {
                CommunityUser::where(['community_id'=> $request->community_id,'user_id'=> $request->user_id])->update(['status' => 2]);


                $communityJoinerDetails  =       User::getUserDetails($request->user_id);

                $temp['type']            =      'CommunityRequestAccepted';
                $temp['user_id']         =      $communityJoinerDetails['id'];
                $temp['link_id']         =      $communityDetails->id;
                $temp['title']           =      "Community Request Approved.";
                $temp['description']     =      "You became the part of Community (".$communityDetails['name'].") ";
                dispatch(new SendPushNotificationJob($communityJoinerDetails, $temp));

                $this->message      =   "Request Accepted successfully";

            }elseif ($request->has('type')  && $request->type == "REJECT") {
                CommunityUser::where(['community_id'=> $request->community_id,'user_id'=> $request->user_id])->delete();
                $communityJoinerDetails  =       User::getUserDetails($request->user_id);

                $temp['type']            =      'CommunityRequestRejected';
                $temp['user_id']         =      $communityJoinerDetails['id'];
                $temp['link_id']         =      $communityDetails->id;
                $temp['title']           =      "Community Request Rejected.";
                $temp['description']     =      "Your requested for joining Community (".$communityDetails['name'].") Rejected.";
                dispatch(new SendPushNotificationJob($communityJoinerDetails, $temp));

                $this->message      =   "Request Rejected successfully";

            }elseif ($request->has('type')  && $request->type == "TRASH") {
                // delete community, its image (logic is in model file), all joiners, all posts and their attachments


                $communityUserList =  CommunityUser::with('user')->where("community_id",$request->community_id)->get();

                if(!empty($communityUserList)){
                    foreach($communityUserList as $row){
                        $user   =  $row->user->toArray();
                        $temp['type']            =      'CommunityDeleted';
                        $temp['user_id']         =      $user['id'];
                        $temp['link_id']         =      $communityDetails->id;
                        $temp['title']           =      "Community removed.";
                        $temp['description']     =      "Community (".$communityDetails['name'].") has been removed from the application.";
                        dispatch(new SendPushNotificationJob($user, $temp)); // send notification to joiner
                    }
                }

                CommunityUser::where(['community_id'=> $request->community_id])->delete(); // delete all joiners
                $communityDetails->delete(); // deleteCommunity

                $this->message      =   "Community deleted successfully";

            }
            DB::commit();
            return $this->sendResponse($this->data,$this->message);
        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message = __(Constant::ERROR_OCCURRED);
            if(env('APP_DEBUG')){
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }


    public function reportCommunity(CommunityReportRequest $request)
    {

        DB::beginTransaction();
        try {
            $message2                       =       '';
            $communityDetails               =       Community::where('id', $request->community_id)->first();
            $userId                         =       Auth::user()->id;
            if (empty($communityDetails) || $communityDetails->user_id == $userId) {
                $message2 = 'Invalid Action';
            }

            $alreadyCommunityReport     =   CommunityReport::where(['community_id'=> $request->community_id,'user_id'=>$userId])->first();

            if (!empty($alreadyCommunityReport)) {
                $message2 = 'You already reported this community.';
            }
            if($message2 != ''){
                return $this->sendError($message2);
            }

            CommunityReport::create(['community_id'=> $request->community_id,'user_id'=>$userId,'type'=>$request->type,'message'=>$request->message]);
            Community::where(['id'=> $request->community_id])->update(['last_reported_at' => date('Y-m-d H:i:s')]);

            $adminDetails            =       User::getUserDetails(1);
            $temp['type']            =      'CommunityReported';
            $temp['user_id']         =      $adminDetails['id'];
            $temp['link_id']         =      $communityDetails->id;
            $temp['title']           =      "Community Reported.";
            $temp['description']     =      "One of user reported community (".$communityDetails['name'].") ";
            dispatch(new SendPushNotificationJob($adminDetails, $temp));
            DB::commit();
            $this->message  =   "Community Reported Successfully.";
            return $this->sendResponse($this->data,$this->message);

        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message  =  CommonService::getExceptionError($e);
        }
        return $this->sendError($this->message);
    }

    public function changeArchiveStatus(ArchiveCommunityRequest $request)
    {
        DB::beginTransaction();
        try {
            $userId                          =       Auth::user()->id;
            CommunityUser::where(['community_id'=> $request->community_id,'user_id'=>$userId])->update(['archive' =>$request->type]);
            if($request->type == 1){
                $this->message  = "Community archived successfully.";
            }else{
                $this->message  = "Community unarchive successfully.";
            }
            DB::commit();
            return $this->sendResponse($this->data,$this->message);
        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message  =  CommonService::getExceptionError($e);

        }
        return $this->sendError($this->message);
    }
}
