<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Community;
use App\Models\CommunityUser;
use Config,Redirect,Session;
use App\Jobs\SendPushNotificationJob;
use App\Services\GeneralService;
class CommunityController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        define("COMMUNITIES_ID","communities.id");
        define("USERS_ID","users.id");

        View::share([
            'title' =>  __('messages.community'),
            'listRoute' => 'admin.community.index',
            'formId' => "communityForm",
            'mainTable' => "communities",
            'listUrl' => "community-list",
            'viewPermission' => "COMMUNITIES_MANAGEMENT_VIEW",
            'section' =>   __('messages.communitiesManagement'),
        ]);

    }

    /**
     * List Records
     */
    public function index(Request $request)
    {

        $DB                          =   Community::query();
        $searchVariableCommunity        =   array();
        $searchDataCommunity             =   Input::all();
        $DB                          =   $this->addJoins($DB);
        if ((Input::all()) || isset($searchDataCommunity['display']) || isset($searchDataCommunity['page'])) {
            if (!empty($searchDataCommunity['name'])) {
                $DB->where('communities.name', 'like', '%' . $searchDataCommunity['name'] . '%');
                $searchVariableCommunity =   array_merge($searchVariableCommunity, array('name' => $searchDataCommunity['name']));
            }
            if (!empty($searchDataCommunity['type'])) {
                $DB->where('communities.type', 'like', '%' . $searchDataCommunity['type'] . '%');
                $searchVariableCommunity =   array_merge($searchVariableCommunity,
                array('type' => $searchDataCommunity['type']));
            }
            $output = addDateFilter($searchDataCommunity, $searchVariableCommunity, 'communities.created_at', $DB);
            extract($output);
            $searchVariableCommunity       =   $searchVariable;
        }


        $sortBy                 =   (Input::get('sortBy')) ? Input::get('sortBy') : 'communities.created_at';
        $order                  =   (Input::get('order')) ? Input::get('order')   : 'DESC';
        $DB->orderBy($sortBy, $order);

        $result = $DB->paginate(GeneralService::getSettings('pageLimit'));
        $complete_string        =   Input::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string           =   http_build_query($complete_string);
        $result->appends(Input::all())->render();
        $statusChangeUrl        =    'admin/community-list/status/';
        return  View('admin.community.index', compact('result', 'searchVariableCommunity', 'sortBy', 'order', 'query_string','statusChangeUrl'));


    }






    /**
     * View Record
     */

     public function view($ide)
     {
         $dbC             =      Community::query();
         $dbC             =      $this->addJoins($dbC);
         $data           =       $dbC->where(COMMUNITIES_ID, $ide)->first();

         if (!$data) {
             return abort(403);
         }
         return  View('admin.community.view')->with(['data' => $data]);
     }
    public function addJoins($dbC){

        $dbC->leftJoin('users', function ($join) {
            $join->on(USERS_ID, '=', 'communities.user_id');
        });

        $dbC->leftJoin('blogs', function ($join) {
            $join->on(COMMUNITIES_ID, '=', 'blogs.community_id');
        });


        $dbC->selectRaw('communities.*,(SELECT COUNT(*) FROM community_users WHERE communities.id=community_users.community_id) AS totalPeople,count(blogs.id) as totalPosts, users.id as userId,users.full_name as fullName,users.username as userName,
        communities.id as communityId,communities.name as communityName,communities.type as communityType');
        $dbC->groupBy(COMMUNITIES_ID);
        return $dbC;

    }

    public function status($ide, $values)
    {

        try {
            $DB = new Community();
            $DB->where('id', $ide)->update(['status' => $values, 'updated_at' => date('Y-m-d h:i:s')]);


        if($values == 0 || $values == '0'){
                $DB = new CommunityUser();
                $results  = $DB->leftJoin('users', function ($join) {
                    $join->on(USERS_ID, '=', 'community_users.user_id');
                })->leftJoin('communities', function ($join) {
                    $join->on(COMMUNITIES_ID, '=', 'community_users.community_id');
                })
                ->select(USERS_ID,'users.full_name','communities.name','device_token','device_type','notification_status')
                ->where('users.notification_status', 1)->where('users.status', 1)
                ->where('community_users.community_id', $ide)->get()->toArray();

                if (count($results) > 0) {
                    $temp = [];
                    foreach ($results as $val) {
                        $temp['user_id']        = $val['id'];
                        $temp['title']          = "Community Deactivated.";
                        $temp['description']    = "Community (".$val['name'].") is deactivated by the app admin. Once the community is activated you will be able to view the community";
                        $temp['type']            = 'AdminNotification';
                        $temp['sent_by_admin']   = 1;
                        try {
                            dispatch(new SendPushNotificationJob($val, $temp));
                        } catch (\Exception $exception) {
                            return $exception->getMessage();
                        }
                    }
                }
            }
            Session::flash('success', trans('messages.statusUpdated'));
            return Redirect::back()->withInput();

        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

}
