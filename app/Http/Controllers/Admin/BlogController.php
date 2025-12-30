<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Blog;
use App\Services\GeneralService;
class BlogController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');


        define("VIEW_PATH","admin.blog.index");

        View::share([
            'title' =>  __('messages.publicPost'),
            'listRoute' => VIEW_PATH,
            'formId' => "blogForm",
            'mainTable' => "blogs",
            'listUrl' => "post-list",
            'viewPermission' => "POSTS_MANAGEMENT_VIEW",
            'section' =>   __('messages.publicPostsManagement'),
        ]);

    }

    /**
     * List Records
     */
    public function index(Request $request)
    {


        $DB                          =   Blog::query();
        $searchVariableBlog         =   array();
        $searchDataBlog             =   Input::all();
        $DB                          =   $this->addJoinsBlog($DB);
        if ((Input::all()) || isset($searchDataBlog['display']) || isset($searchDataBlog['page'])) {
            if (!empty($searchDataBlog['name'])) {
                $DB->where('users.username', 'like', '%' . $searchDataBlog['name'] . '%');
                $searchVariableBlog =   array_merge($searchVariableBlog, array('name' => $searchDataBlog['name']));
            }
            if (isset($searchDataBlog['type']) && $searchDataBlog['type'] != '' && $searchDataBlog['type'] != 0) {
                $DB->where('communities.type', '=',   $searchDataBlog['type'] );
                $searchVariableBlog =   array_merge($searchVariableBlog, array('type' => $searchDataBlog['type']));
            }
            else if (isset($searchDataBlog['type']) && $searchDataBlog['type'] == 0) {
                $DB->where('blogs.community_id', '=',   $searchDataBlog['type'] );
                $searchVariableBlog =   array_merge($searchVariableBlog, array('type' => $searchDataBlog['type']));
            }
            $output = addDateFilter($searchDataBlog, $searchVariableBlog, 'blogs.created_at', $DB);
            extract($output);
            $searchVariableBlog        =   $searchVariable;
        }


        $sortBy  = $sortByBlog               =   (Input::get('sortBy')) ? Input::get('sortBy') : 'blogs.created_at';
        $order     = $orderBlog               =   (Input::get('order')) ? Input::get('order')   : 'DESC';
        $DB->orderBy($sortByBlog, $orderBlog);

        $result = $DB->paginate(GeneralService::getSettings('pageLimit'));
        $completeStringBlog        =   Input::query();
        unset($completeStringBlog["sortBy"]);
        unset($completeStringBlog["order"]);
        $query_string           =   http_build_query($completeStringBlog);
        $result->appends(Input::all())->render();

        $statusChangeUrl        =    'admin/post-list/status/';

        return  View(VIEW_PATH, compact('result', 'searchVariableBlog', 'sortBy', 'order', 'query_string','statusChangeUrl'));


    }






    /**
     * View Record
     */

     public function view($id)
     {
         $db             =      Blog::query();
         $db             =      $this->addJoinsBlog($db);
         $data           =      $db->where('blogs.id', $id)->first();

         if (!$data) {
             return abort(403);
         }
         return  View('admin.blog.view')->with(['data' => $data]);
     }
    public function addJoinsBlog($DBBlog){

        $DBBlog->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'blogs.user_id');
        });

        $DBBlog->leftJoin('communities', function ($join) {
            $join->on('blogs.community_id', '=', 'communities.id');
        });

        $DBBlog->select('blogs.*', 'users.id as userId','users.full_name as fullName','users.username as userName','communities.name as communityName','communities.type as communityType');

        return $DBBlog;

    }

    public function status($recordId, $val)
    {

        try {
            $DB = new Blog();
            $DB->where('id', $recordId)->update(['status' => $val, 'updated_at' => date('Y-m-d h:i:s')]);
            return redirect()->route(VIEW_PATH)
                ->with('success', trans('messages.statusUpdated'));
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }

}
