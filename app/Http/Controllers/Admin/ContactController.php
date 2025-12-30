<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ContactDetail;
use App\Models\SupportComment;
use App\Models\UserNotification;
use App\Services\GeneralService;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Validation\Rule;
use App\Jobs\SendPushNotificationJob;
use Illuminate\Http\Request;
use  Hash, DB,  Config, Validator, Session, Redirect,  Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use App\Models\UserPermission;
use App\Models\NotificationHistory;
use App\Services\CommonService;
use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketReply;
use App\Http\Requests\SupportRequest;
use App\Jobs\SendEmailJob;

class ContactController extends BaseController
{
    protected $paginate;
    protected $zeroZero;
    protected $ticketStatusConfig;

    public function __construct()
    {
		parent::__construct();
        $this->middleware('auth:admin');
        $this->paginate = GeneralService::getSettings('pageLimit');
        $this->ticketStatusConfig = Config::get('constants.TICKET_STATUS');

    }


    public function contactList(Request $request)
    {
        $DBC                         =   ContactDetail::query();
        $fieldsToSearch              =   array('*name-mobile_no-email*' => '*like-like-like*');
        $searchVariable              =   $request->all();
        $extraConditions             =   [];
        $output                      =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DBC, 'created_at',$extraConditions);
        extract($output);
        \View::share([
            'section' =>   __('messages.ContactManagement'),
        ]);
        return  View('admin.contact.contactList', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    } // end


    public function contactView($id)
    {
        \View::share([
            'section' =>   __('messages.ContactManagement'),
        ]);
        $ticketStatusList = $this->ticketStatusConfig;
        $data = ContactDetail::where('contact_details.id', $id)->select('contact_details.*')->first();
        $repliedMessages = SupportComment::where('support_id', $id)->orderBy('created_at','desc')->get()->toArray();
        return  View('admin.contact.contactView', compact('id','data','repliedMessages','ticketStatusList'));
    }


    public function sendReply(SupportRequest $request)
    {
         $support_id = $request->id;
        $ticket_status = $request->ticket_status;

        $support = ContactDetail::find($support_id);

        $reply_user_id = $support->user_id;
      
        $dataUser = User::select('id','full_name','email')->where(['id'=>$reply_user_id])->first();
      
        $error = '';
        if(empty($dataUser)){
            $support->status = 1;
            if($ticket_status!=''){
            $support->ticket_status = $ticket_status;
            }
            $support->save();
            Session::flash('error', trans("user does not exist"));
            return Redirect::to('admin/contact-list');
        }

        $full_name = isset($dataUser['full_name']) ? $dataUser['full_name'] : '';
        $dataMessage = ['name' => $full_name, 'message' => $request->message];

         //update support
         $support->status = 1;
         if($ticket_status!=''){
         $support->ticket_status = $ticket_status;
         }
         $support->save();

         //add support comment
         $SupportComment = new SupportComment();
         $SupportComment->support_id = $support_id;
         $SupportComment->user_id =  $reply_user_id;
         $SupportComment->description =  $request->message;
         $SupportComment->save();

        $emailData = [
            'replaceData' => [$full_name, $support_id,$request->message],
            'email' =>$dataUser['email'], 'email_type' => 'support_reply'
        ];
    try {
        dispatch(new SendEmailJob($emailData));
    } catch (\Exception $e) {
        $this->message =   $e->getMessage();
    }

        if ($error != "") {

            Session::flash('error', trans("messages.somethingWentWrong"));
            return Redirect::back()->withInput();
        }
        Session::flash('success', trans("messages.replySent"));
        return Redirect::to('admin/contact-list');
    }



}
