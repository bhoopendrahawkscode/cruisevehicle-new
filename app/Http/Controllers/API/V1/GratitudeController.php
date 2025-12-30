<?php

namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\GeneralService;
use App\Models\Attachment;
use  Auth, DB;
use App\Http\Requests\API\GratitudeRequest;
use App\Models\GratitudeAttachment;
use App\Http\Resources\GratitudeResource;
use App\Models\Gratitude;

class GratitudeController extends BaseController
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


    public function getGratitudes(Request $request)
    {
        try {
            $userId                 =        Auth::user()->id;
            $dataList               =        Gratitude::with('gratitude_attachments')->where(['user_id' => $userId])
                                            ->orderBy("title", "asc")
                                            ->paginate($this->paginateAll);
            return $this->sendResponse(GratitudeResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function saveGratitudeAttachments($request,$gratitudeData){
        $attachments  = [];
        if(!empty($request->attachments)){
            foreach($request->attachments as $row){
                $attachments[]  = ['gratitude_id'=>$gratitudeData->id,'url'=>$row['url'],'type'=>$row['type']];
            }
            if(!empty($attachments)){
                GratitudeAttachment::insert($attachments);
                foreach($request->attachments as $row){
                    Attachment::where('url', $row['url'])->delete();
                }
            }
        }
    }
    public function saveGratitude(GratitudeRequest $request)
    {

        DB::beginTransaction();
        try {

            $userId                          =       Auth::user()->id;
            $gratitudeData                   =       [];
            if($request->id != ''){
                $gratitudeData               =       Gratitude::where('id', $request->id)
                ->where('user_id', $userId)->first();
            }else{
                $gratitudeData               =       new Gratitude();
            }

            $gratitudeData['user_id']         =       $userId;
            $gratitudeData['title']           =       $request->title;
            $gratitudeData['description']     =       $request->description;
            if($gratitudeData->save()){
                $this->saveGratitudeAttachments($request,$gratitudeData);
            }

            DB::commit();
            return $this->sendResponse($this->data,
            trans('api.GRATITUDE_SAVED'));

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

    public function deleteGratitude(Request $request)
    {
        try {
            $userId                         =       Auth::user()->id;
            $gratitudeDetails               =       Gratitude::with('gratitude_attachments')->where('id', $request->gratitude_id)->first();

            if (empty($gratitudeDetails) || $gratitudeDetails->user_id != $userId) {
                return $this->sendError("Invalid Action.");
            }
            if(isset($gratitudeDetails->gratitude_attachments) && !empty($gratitudeDetails->gratitude_attachments)){
                foreach($gratitudeDetails->gratitude_attachments as $row){
                    $row->delete(); // delete attachments
                }
            }
            $gratitudeDetails->delete();
            return $this->sendResponse($this->data, "Gratitude deleted successfully.");
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

}
