<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
use App\Models\InsuranceCoverType;
use App\Services\CommonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;

class InsuranceCoverTypeController extends BaseController
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
        $this->listRoute = 'admin.insuranceCoverType.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName = 'Quote';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.quote'),
            'statusPermission' => "INSURANCE_COVER_TYPE_STATUS",
        ]);
    }

    public function list(Request $request)
    {
        $DB = InsuranceCoverType::query();

        $fieldsToSearch = ['name' => 'like','cover_type' => '=', 'status' => '='];
        $searchVariable = $request->all();

        $output = getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        $statusChangeUrl        =    'admin/insurance-management/insurance-cover-type/status/';
        return view('admin.insuranceCoverType.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string','statusChangeUrl'));
    }



    public function status($id, $status)
    {
        $insuranceCoverType = InsuranceCoverType::findOrFail($id);

        try {
            DB::beginTransaction();
            $insuranceCoverType->update([
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

    public function view(InsuranceCoverType $insuranceCoverType)
    {
        return view('admin.insuranceCoverType.view', compact('insuranceCoverType'));
    }
}
