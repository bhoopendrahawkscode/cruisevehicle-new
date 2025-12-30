<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Transaction;
use Config;
use App\Services\GeneralService;
class TransactionController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');

        View::share([
            'title' =>  __('messages.transaction'),
            'listRoute' => 'admin.transaction.index',
            'formId' => "transactionForm",
            'mainTable' => "transactions",
            'listUrl' => "transaction-list",
            'viewPermission' => "TRANSACTION_MANAGEMENT_VIEW",
            'section' =>   __('messages.transactionManagement'),
        ]);

    }

    /**
     * List Records
     */
    public function index(Request $request)
    {

        $DB                          =   Transaction::query();
        $searchVariableTrans         =   array();
        $searchDataTrans             =   Input::all();
        $DB                          =   $this->addJoins($DB);
        if ((Input::all()) || isset($searchDataTrans['display']) || isset($searchDataTrans['page'])) {
            if (!empty($searchDataTrans['name'])) {
                $DB->where('transaction_id', 'like', '%' . $searchDataTrans['name'] . '%');
                $searchVariableTrans =   array_merge($searchVariableTrans, array('name' => $searchDataTrans['name']));
            }
            $output = addDateFilter($searchDataTrans, $searchVariableTrans, 'transactions.created_at', $DB);
            extract($output);
            $searchVariableTrans        =   $searchVariable;
        }


        $sortByTrans     = $sortBy            =   (Input::get('sortBy')) ? Input::get('sortBy') : 'transactions.created_at';
        $orderTrans      = $order             =   (Input::get('order')) ? Input::get('order')   : 'DESC';
        $DB->orderBy($sortByTrans, $orderTrans);

        $result = $DB->paginate(GeneralService::getSettings('pageLimit'));
        $complete_stringTrans        =   Input::query();
        unset($complete_stringTrans["sortBy"]);
        unset($complete_stringTrans["order"]);
        $query_string           =   http_build_query($complete_stringTrans);
        $result->appends(Input::all())->render();

        return  View('admin.transaction.index', compact('result', 'searchVariableTrans', 'sortBy', 'order', 'query_string'));


    }






    /**
     * View Record
     */

     public function view($id)
     {
         $db             =      Transaction::query();
         $db             =      $this->addJoins($db);
         $data           =      $db->where('transactions.id', $id)->first();

         if (!$data) {
             return abort(403);
         }
         return  View('admin.transaction.view')->with(['data' => $data]);
     }
    public function addJoins($DB){

        $DB->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'transactions.user_id');
        });

        $DB->leftJoin('subscription_translations', function ($join) {
            $join->on('transactions.subscription_id', '=', 'subscription_translations.subscription_id')->where('subscription_translations.language_id', '=', 1);

        });

        $DB->select('transactions.*', 'users.id as userId','users.full_name as fullName','users.username as userName','subscription_translations.name as subscriptionName');

        return $DB;

    }

}
