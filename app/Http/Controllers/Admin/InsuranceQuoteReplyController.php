<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\InsuranceQuoteReplyRequest;
use App\Models\InsuranceCoverPeriod;
use App\Models\InsuranceRenewal;
use App\Models\CarModel;
use App\Models\InsuranceCoverType;
use App\Models\InsuranceQuoteReply;
use App\Models\User;
use App\Services\CommonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class InsuranceQuoteReplyController extends BaseController
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
        $this->listRoute = 'admin.insuranceQuoteReply.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName = 'Quote Reply';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.quote'),
            'models'=> ['' => __('messages.select_models')]+CarModel::whereStatus(true)->orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }


    public function list(Request $request)
    {
        $DB = InsuranceQuoteReply::query();

        $fieldsToSearch = [
            'request_reference_number' => 'like',
            'cover_type' => '=',
            'company_id' => '=',
        ];

        $searchVariable = $request->all();

        $output = getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);

        return view('admin.insuranceQuoteReply.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }




    public function edit(InsuranceQuoteReply $insuranceQuoteReply)
    {
        $model = $insuranceQuoteReply;
        return view('admin.insuranceQuoteReply.edit', compact('model'));
    }

    public function Update(Request $request,  InsuranceQuoteReply $insuranceQuoteReply)
    {
        try {
            DB::beginTransaction();
            $editableFields = $request->only([
                'premium_proposed',
                'comment',
                'status'
            ]);

            $insuranceQuoteReply->update($editableFields);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success',  __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }
    public function view(InsuranceQuoteReply $insuranceQuoteReply)
    {
        return view('admin.insuranceQuoteReply.view', compact('insuranceQuoteReply'));
    }

    public function replyForm($id)
    {
        $insuranceQuote = InsuranceQuoteReply::findOrFail($id);
        return view('admin.insuranceQuoteReply.reply', compact('insuranceQuote'));
    }


    public function replySubmit(InsuranceQuoteReplyRequest $request)
    {
        InsuranceQuoteReply::create($request->validated());
        return redirect()->route('admin.insuranceQuoteReply.list')->with('success', __('messages.replied'));
    }



}
