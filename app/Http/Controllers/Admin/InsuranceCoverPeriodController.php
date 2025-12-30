<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
use App\Models\InsuranceCoverPeriod;
use App\Services\CommonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class InsuranceCoverPeriodController extends BaseController
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
        $this->listRoute = 'admin.insuranceCoverPeriod.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName = 'Quote';



        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.quote'),
            'statusPermission' => "GOOD_NEWS_MANAGEMENT_CHANGE_STATUS",
        ]);
    }

    public function list(Request $request)
    {

        $DB = InsuranceCoverPeriod::query();

        $fieldsToSearch = ['name' => 'like','cover_type' => '=', 'status' => '='];
        $searchVariable = $request->all();

        $output = getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        $statusChangeUrl        =    'admin/insurance-management/insurance-cover-period/status/';

        return view('admin.insuranceCoverPeriod.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }

    public function status($id, $status)
    {
        $insuranceCoverPeriod = InsuranceCoverPeriod::findOrFail($id);

        try {
            DB::beginTransaction();
            $insuranceCoverPeriod->update([
                'status' => $status
            ]);
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS, __('messages.statusUpdated'));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function view(InsuranceCoverPeriod $insuranceCoverPeriod)
    {
        return view('admin.insuranceCoverPeriod.view', compact('insuranceCoverPeriod'));
    }

}
