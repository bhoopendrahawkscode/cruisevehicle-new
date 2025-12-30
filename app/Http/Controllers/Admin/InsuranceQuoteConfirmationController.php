<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\InsuranceQuoteConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class InsuranceQuoteConfirmationController extends BaseController
{

    protected $mainTable;
    protected $foreignKey;
    protected $translationFields;
    protected $listRoute;
    protected $imageFolder;
    protected $successName;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->listRoute = 'admin.insuranceQuoteConfirmation.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName = 'Quote';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.quote'),
            'statusPermission' => "INSURANCE_QUOTE_CONFIRMATION_LIST",
        ]);
    }

    public function list(Request $request)
    {
        $DB = InsuranceQuoteConfirmation::query()
            ->join('users', 'insurance_quote_confirmations.user_id', '=', 'users.id')
            ->select('insurance_quote_confirmations.*', 'users.full_name as user_name');

        $fieldsToSearch = ['request_reference_number' => 'like', 'user_name' => 'like', 'status' => '='];
        $searchVariable = $request->all();

        $output = getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);

        return view('admin.insuranceQuoteConfirmation.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

}
