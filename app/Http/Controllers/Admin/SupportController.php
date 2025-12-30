<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use App\Models\SupportComment;
use Illuminate\Support\Facades\Request as Input;

use Illuminate\Http\Request;
use DB,Config,Session,Redirect,Auth;
use App\Mail\TicketReply;
use Illuminate\Support\Facades\Mail;
use App\Services\GeneralService;
use App\Http\Requests\SupportRequest;
use App\Http\Controllers\Admin\BaseController;
class SupportController extends BaseController
{
    protected $paginate;
    public function __construct(){
		parent::__construct();
        $this->middleware('auth:admin');
        $this->paginate = GeneralService::getSettings('pageLimit');
    }

    public function index(Request $request){
        $DB                     =   Support::query();
        $searchVariable         =   array();
        $searchData               =   Input::all();
        if ((Input::all()) ||isset($searchData['display']) || isset($searchData['page']) ) {

            $output = addDateFilter($searchData,$searchVariable,'supports.created_at',$DB);
            extract($output);



            foreach($searchData as $fieldName => $fieldValue){
				if($fieldName == 'status' && $fieldValue != '' ){
					$DB->where("supports.status",'=',$fieldValue);
				}elseif(!empty($fieldValue)){
                    $phoneSq = 'REPLACE(users.mobile_no, "-", "")';
					$DB->where(function ($q) use($phoneSq,$fieldValue) {
                        $fieldValue = str_replace("-","",$fieldValue);
						$q->whereRaw("{$phoneSq} LIKE '%{$fieldValue}%'")
                        ->orWhereRaw("full_name LIKE '%{$fieldValue}%'")
                        ->orWhereRaw("email LIKE '%{$fieldValue}%'");
					});
                }
                $searchVariable =   array_merge($searchVariable,array($fieldName => $fieldValue));
            }
        }
        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'supports.updated_at';
        $order  = (Input::get('order')) ? Input::get('order')   : 'asc';
        $result =   $DB->select('supports.*','users.full_name','users.email','users.country','users.mobile_no')
                    ->leftJoin('users', 'users.id', '=', 'supports.user_id')
                    ->orderBy($sortBy,$order)
                    ->paginate($this->paginate);

        $complete_string        =   Input::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string           =   http_build_query($complete_string);
        $result->appends(Input::all())->render();
            return  View('admin.support.index', compact('result' ,'searchVariable','sortBy','order','query_string'));
    }






    public function get($id){
        $DB             = new Support();
        $data = $DB->WHERE('id',$id)->with(['comments','comments.user'])->first();
        if($data->status == 2){
            Session::flash('error', trans("messages.somethingWentWrong"));
            return Redirect::to('admin/support-list');
        }else{
            return  View('admin.support.edit',compact('data'));
        }
    }

    public function update($id, SupportRequest $request)
{
    $data      = [];
    $DBS       = new Support;
    $DB        = new SupportComment();

    $data1['status'] = 2;
    $DBS->where('id', $id)->update($data1);

    $ide = $DB->insertGetId([
        'support_id'   => $id,
        'user_id'      => Auth::user()->id,
        'description'  => $request->message,
    ]);

    $data = $DBS->where('id', $id)->with(['user'])->first();
    $full_name = isset($data->user) ? $data->user->full_name : '';
    $datax = ['message' => $request->message, 'ticket_id' => $id, 'full_name' => $full_name];

    try {
        if (isset($data->user)) {
            Mail::to($data->user->email)->send(new TicketReply($datax));
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
        die;
        // Block of code to handle errors
    }

    if (!$ide) {
        DB::rollback();
        Session::flash('error', trans("messages.somethingWentWrong"));
        return Redirect::back()->withInput();
    }

    Session::flash('success', trans("messages.replySent"));
    return Redirect::to('admin/support-list');
}

}
?>
