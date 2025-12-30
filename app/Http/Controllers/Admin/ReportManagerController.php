<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ReportManager;

use Illuminate\Support\Facades\Request as Input;

use Illuminate\Http\Request;
use Response, Hash, DB, App, Config, Validator, Session, Redirect, URL, Auth, File;
use App\Jobs\SendPushNotificationJob;
class ReportManagerController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:admin');
        $this->paginate = Config::get('constants.ADMIN_PAGINATION_LIMIT');
    }
    public function index(Request $request)
    {

        $DB                     =   ReportManager::query();
        $searchVariable         =   array();
        $searchData               =   Input::all();

        if ((Input::all()) || isset($searchData['display']) || isset($searchData['page'])) {

            foreach ($searchData as $fieldName => $fieldValue) {

                if ($fieldName == 'user_name') {
                    $DB->whereHas('reported_user', function ($q) use ($fieldValue) {
                        $q->where('full_name', 'like', '%' . $fieldValue . '%');
                    });
                }

                if ($fieldName == 'status') {
                    $DB->whereHas('reported_user', function ($q) use ($fieldValue) {
                        $q->where('status', 'like', '%' . $fieldValue . '%');
                    });
                }

                $searchVariable =   array_merge($searchVariable, array($fieldName => $fieldValue));
            }
        }

        $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'users.updated_at';
        $order  = (Input::get('order')) ? Input::get('order')   : 'desc';


        $result =  $DB->selectRaw('MAX(reports.id) as id, to_id, count(*) as count')->with('reported_user')->leftJoin('users', 'users.id', '=', 'to_id')
            ->groupBy('to_id')
			 ->orderBy($sortBy,$order)
            ->paginate($this->paginate);

        $complete_string        =   Input::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string           =   http_build_query($complete_string);
        $result->appends(Input::all())->render();

        return  View('admin.reportmanager.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }


    public function view($id)
    {
        try {

            $DB                     =   ReportManager::query();
            $searchVariable         =   array();
            $inputGet               =   Input::all();
            $sort_by_data           =   Input::get("sortBy");

            if ((Input::all()) || isset($inputGet['display']) || isset($inputGet['page'])) {
                $searchData         =   Input::all();
                unset($searchData['display']);
                unset($searchData['_token']);

                if (isset($searchData['order'])) {
                    unset($searchData['order']);
                }
                if (isset($searchData['sortBy'])) {
                    unset($searchData['sortBy']);
                }
                if (isset($searchData['page'])) {
                    unset($searchData['page']);
                }
            }
            $sortBy = (Input::get('sortBy')) ? Input::get('sortBy') : 'id';
            $order  = (Input::get('order')) ? Input::get('order')   : 'asc';

            $result =   $DB->select('*')
                ->where("to_id", '=',  $id)
                ->orderBy($sortBy, $order)
                ->paginate($this->paginate);

            $complete_string        =   Input::query();
            unset($complete_string["sortBy"]);
            unset($complete_string["order"]);
            $query_string           =   http_build_query($complete_string);
            $result->appends(Input::all())->render();
            return  View('admin.reportmanager.reportinglist', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string', 'sort_by_data'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

    public function status($Id, $value)
    {
        try {
            $DB = new User();
            $DB->where('id', $Id)->update(['status' => $value, 'updated_at' => date('Y-m-d h:i:s')]);
            // User Inactive Status Case Send Notification to user
            if ($value == 0) {
                $user_data = User::where('id', $Id)->first();
                if (!empty($user_data)) {
                    $title = trans('messages.profileDeactivated');
                    $description = trans('messages.profileDeactivatedInappropriateContent');
                    $notification_data = ["user_id" => $user_data->id,  "type" => "AdminNotification","title" => $title, "description" => $description];
                    dispatch(new SendPushNotificationJob($user_data, $notification_data));
                }
            }
            return redirect()->route('admin.ReportManagerlist')
                ->with('success', trans('messages.statusUpdated'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
