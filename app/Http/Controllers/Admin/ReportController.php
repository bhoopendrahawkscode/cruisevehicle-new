<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Community;
use App\Models\Blog;
use App\Models\Transaction;
use App\Models\CompanyInsuranceRenewal;
use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Vehicle;
use App\Models\ServiceProvider;
use App\Models\InsuranceRenewal;
use App\Services\MetaService;
use App\Exports\InsuranceRenewalsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class ReportController extends BaseController
{
    const ROLE_SLUG = 'roles.slug';
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->zeroZero = Constant::ZERO_ZERO;
        $this->fullFull = Constant::FULL_FULL;
        \View::share([
            'section' =>   __('messages.ReportManager '),
        ]);
    }

    public function index(Request $request)
{

    $searchData = $request->all();
    $query = $this->getQuery($searchData);
    $Companyquery = $this->getCompanyQuery($searchData);
    $company     =   User::whereHas('roles', function ($q) {
        $q->where('roles.slug', 'insurance_manager');
    })->pluck('full_name', 'id')->toArray();

    $totalUser = $this->getUserCount($query);
    $totalRegisteredVehicles = Vehicle::where($query)->count();
    $totalServiceProvider = ServiceProvider::where($query)->count();
    $insuranceCompanyId=isset($searchData['company']) ? $searchData['company'] :'';
    $insuranceRenewals = InsuranceRenewal::where($query)->count();
    $insuranceRenewed = CompanyInsuranceRenewal::where($this->getCompanyQuery($searchData))
    ->join('insurance_renewal', 'company_insurance_renewals.insurance_renewal_id', '=', 'insurance_renewal.id')
    ->whereIn('company_insurance_renewals.status', [3, 4])
    ->select('company_insurance_renewals.*', 'insurance_renewal.*') // Select specific columns if needed
    ->get();
$totalInsuranceRenewal=$insuranceRenewals;
$insuranceRenewed=count($insuranceRenewed);
    return view('admin.report', compact(
        'totalUser',
        'searchData',
        'totalServiceProvider',
        'totalRegisteredVehicles',
         'totalInsuranceRenewal',
         'insuranceRenewed',
         'company'
    ));
}

private function getQuery($searchData)
{
    $query = [
    ];
    if (isset($searchData['status']) && $searchData['status'] != '') {
        $query[] = ['status', '=', $searchData['status']];
    }
    if (isset($searchData['from']) && $searchData['from'] != '') {
        $query[] = ['created_at', '>=', $searchData['from'] . $this->zeroZero];
    }

    if (isset($searchData['to']) && $searchData['to'] != '') {
        $query[] = ['created_at', '<=', $searchData['to'] . $this->fullFull];
    }
  

    return $query;
}

private function getCompanyQuery($searchData)
{
    $query = [
    ];
  
    if (isset($searchData['from']) && $searchData['from'] != '') {
        $query[] = ['insurance_renewal.created_at', '>=', $searchData['from'] . $this->zeroZero];
    }

    if (isset($searchData['to']) && $searchData['to'] != '') {
        $query[] = ['insurance_renewal.created_at', '<=', $searchData['to'] . $this->fullFull];
    }

    return $query;
}

public function downloadInsuranceRenewalsReport(Request $request)
{
    $searchData = $request->all();
    return Excel::download(new InsuranceRenewalsExport($searchData), 'InsuranceRenewalsReport.xlsx');
}

private function getUserCount($query)
{
    return User::where($query)->whereHas(Constant::ROLES, function ($q) {
        return $q->where(self::ROLE_SLUG, constant::USER);
    })->count();
}

private function getSubscriptionCount($query, $subscriptionType)
{
    return User::where($query)
        ->whereNotNull($subscriptionType)
        ->whereHas(Constant::ROLES, function ($q) {
            return $q->where(self::ROLE_SLUG, constant::USER);
        })
        ->count();
}

private function getSubAdminCount()
{
    return User::whereHas(Constant::ROLES, function ($q) {
        return $q->where(self::ROLE_SLUG, constant::SUB_ADMIN);
    })->where('is_deleted', 0)->count();
}

}
