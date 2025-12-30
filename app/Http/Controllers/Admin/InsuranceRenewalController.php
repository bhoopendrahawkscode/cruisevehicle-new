<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
use App\Models\InsuranceCoverPeriod;
use App\Models\InsuranceRenewal;
use App\Models\CarModel;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\CompanyInsuranceRenewal;
use App\Services\CommonService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Config;
use App\Jobs\SendPushNotificationJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class InsuranceRenewalController extends BaseController
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
        $this->listRoute = 'admin.insuranceRenewal.list';
        $this->imageFolder = Config::get('constants.IMAGE_FOLDER');
        $this->successName = 'Quote';


        View::share([
            'listRoute' => $this->listRoute,
            'title' => trans('messages.quote'),
            'statusPermission' => "GOOD_NEWS_MANAGEMENT_CHANGE_STATUS",
            'models' => ['' => __('messages.select_models')] + CarModel::whereStatus(true)->orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }


    public function list(Request $request)
    {

        $roles = Auth::user()->roles;
        $roleName =  $roles->pluck('slug')->first();
        if ($roleName == 'admin'|| $roleName == 'sub_admin') {
            if ($request->query('company')) {

                $DB = CompanyInsuranceRenewal::query()
            ->where('company_insurance_renewals.company_id', $request->query('company'))
            ->join('insurance_renewal', 'company_insurance_renewals.insurance_renewal_id', '=', 'insurance_renewal.id')
            ->leftJoin('insurance_cover_types', 'insurance_renewal.cover_type', '=', 'insurance_cover_types.id') 
            ->leftJoin('insurance_cover_periods', 'insurance_renewal.period_of_insurance_cover', '=', 'insurance_cover_periods.id')
            ->leftJoin('models', 'insurance_renewal.car_model', '=', 'models.id') // Join for carModel
            ->select(
                'company_insurance_renewals.id as company_insurance_renewal_id','company_insurance_renewals.premium_payable','company_insurance_renewals.comment', 
                'company_insurance_renewals.insurance_renewal_id', 
                'company_insurance_renewals.status as company_insurance_status',
                'insurance_renewal.*',
                'insurance_cover_types.name as cover_type_name',
                'insurance_cover_periods.name as period_insurance_cover_name',
                'models.name as car_model_name'
            );
            // ->orderBy($sortBy, $order);
            //$DB = CompanyInsuranceRenewal::query()->where('company_id', Auth::user()->id);
            $fieldsToSearch  =   ['insurance_status' => '=','*insurance_renewal.full_name-insurance_renewal.id*' => '*like-like*'];
            $searchVariable = $request->all();
            $output = getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'company_insurance_renewals.created_at');
            extract($output);
            return view('admin.insuranceRenewal.list_insurance_manager', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
             
            }else{

                $DB = InsuranceRenewal::query()
                ->leftJoin('models', 'insurance_renewal.car_model', '=', 'models.id') 
                ->leftJoin('insurance_cover_types', 'insurance_renewal.cover_type', '=', 'insurance_cover_types.id') 
                ->leftJoin('insurance_cover_periods', 'insurance_renewal.period_of_insurance_cover', '=', 'insurance_cover_periods.id') 
                ->select(
                    'insurance_renewal.*',
                    'models.name as car_model_name', 
                    'insurance_cover_types.name as cover_type_name', 
                    'insurance_cover_periods.name as period_insurance_cover_name' 
                );
    
               
                 $fieldsToSearch  =   ['*insurance_renewal.full_name-insurance_renewal.id*' => '*like-like*'];
    
                $searchVariable = $request->all();
                $output = getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'insurance_renewal.created_at');
                extract($output);
    
                return view('admin.insuranceRenewal.list', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));

            }



        } else {
            $DB = CompanyInsuranceRenewal::query()
            ->where('company_insurance_renewals.company_id', Auth::user()->id)
            ->join('insurance_renewal', 'company_insurance_renewals.insurance_renewal_id', '=', 'insurance_renewal.id')
            ->leftJoin('insurance_cover_types', 'insurance_renewal.cover_type', '=', 'insurance_cover_types.id') 
            ->leftJoin('insurance_cover_periods', 'insurance_renewal.period_of_insurance_cover', '=', 'insurance_cover_periods.id')
            ->leftJoin('models', 'insurance_renewal.car_model', '=', 'models.id') // Join for carModel
            ->select(
                'company_insurance_renewals.id as company_insurance_renewal_id','company_insurance_renewals.premium_payable','company_insurance_renewals.comment', 
                'company_insurance_renewals.insurance_renewal_id', 
                'company_insurance_renewals.status as company_insurance_status',
                'insurance_renewal.*',
                'insurance_cover_types.name as cover_type_name',
                'insurance_cover_periods.name as period_insurance_cover_name',
                'models.name as car_model_name'
            );
            // ->orderBy($sortBy, $order);
            //$DB = CompanyInsuranceRenewal::query()->where('company_id', Auth::user()->id);
            $fieldsToSearch  =   ['insurance_status' => '=','*insurance_renewal.full_name-insurance_renewal.id*' => '*like-like*'];
            $searchVariable = $request->all();
            $output = getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'company_insurance_renewals.created_at');
            extract($output);
            return view('admin.insuranceRenewal.list_insurance_manager', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
        }
    }

    public function view(InsuranceRenewal $insuranceRenewal)
    {
        
        $result = InsuranceRenewal::query()
        ->where('company_insurance_renewals.company_id', Auth::user()->id)
                ->leftJoin('models', 'insurance_renewal.car_model', '=', 'models.id') 
                ->leftJoin('insurance_cover_types', 'insurance_renewal.cover_type', '=', 'insurance_cover_types.id') 
                ->leftJoin('company_insurance_renewals', 'insurance_renewal.id', '=', 'company_insurance_renewals.insurance_renewal_id') 
                ->leftJoin('insurance_cover_periods', 'insurance_renewal.period_of_insurance_cover', '=', 'insurance_cover_periods.id')
                ->leftJoin('vehicles', 'insurance_renewal.vehicle_id', '=', 'vehicles.id')  
                ->select(
                    'insurance_renewal.*',
                    'models.name as car_model_name', 
                    'vehicles.insurance_certificate as insurance_certificate',
                    'insurance_cover_types.name as cover_type_name', 
                    'company_insurance_renewals.comment as comment', 
                    'company_insurance_renewals.attachment as attachment', 
                    'insurance_cover_periods.name as period_insurance_cover_name' 
                )->where('insurance_renewal.id', $insuranceRenewal->id)->first();

               $company_name= Auth::user()->full_name;
        return view('admin.insuranceRenewal.view',compact('result','company_name'));
    }


    public function submit_status_change(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:company_insurance_renewals,id',
            'status' => 'required|in:accepted,declined',
            'price' => 'nullable|numeric|min:0.01',
            'comment' => 'nullable',
        ]);
        $insuranceRenewal = CompanyInsuranceRenewal::find($validatedData['id']);

        if ($validatedData['status'] === 'accepted') {
            $insuranceRenewal->status = 1;
            $insuranceRenewal->premium_payable = $validatedData['price'];
            $insuranceRenewal->comment = $request->comment;
            // Send email notification to User
            $temp = [];
            $vehicleOwnerDetails = User::getUserDetails($insuranceRenewal->user_id); 
            $temp['type'] = 'InsuranceQuoteSubmitted';
            $temp['user_id'] = $insuranceRenewal->user_id;
            $temp['link_id'] = Auth::user()->id;
            $temp['title'] = "Insurance Quote Submitted";
            $temp['description'] = "An insurance quote has been submitted for your vehicle by " . Auth::user()->full_name . ". Please review and respond.";
           
            dispatch(new SendPushNotificationJob($vehicleOwnerDetails, $temp));
            
            } elseif ($validatedData['status'] === 'declined') {
            $insuranceRenewal->status = 2;
            $insuranceRenewal->premium_payable = null;
            $insuranceRenewal->comment=$request->comment;
        }
        $insuranceRenewal->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
        ]);
    }


    public function submit_certificate(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:company_insurance_renewals,id',
            'vehicle_id' => 'required|exists:vehicles,id'
        ]);

        $vehicle = Vehicle::find($validatedData['vehicle_id']);

        if ($request->hasFile('certificate')) {
            $vehicle->insurance_certificate = $request->file('certificate')->store('certificates');
        }
        if ($vehicle->insurance_expiry_date) {
            $vehicle->insurance_expiry_date = \Carbon\Carbon::parse($vehicle->insurance_expiry_date)->addYear();
        }else {
            $vehicle->insurance_expiry_date = \Carbon\Carbon::now()->addYear();
        }   
        
        $vehicle->save();

            $insuranceRenewal = CompanyInsuranceRenewal::find($validatedData['id']);
            $insuranceRenewal->status = 4;
            $temp = [];
            $vehicleOwnerDetails = User::getUserDetails($insuranceRenewal->user_id); 
            $temp['type'] = 'InsuranceCertificateReleased';
            $temp['user_id'] = $insuranceRenewal->user_id;
            $temp['link_id'] = Auth::user()->id;
            $temp['title'] = "Insurance Certificate Released";
            $temp['description'] = "An insurance Certificate has been Released for your vehicle by " . Auth::user()->full_name . ". Please review and respond.";     
            dispatch(new SendPushNotificationJob($insuranceRenewal, $temp));
            
            $link    =    url(ImageService::getImageUrl($vehicle->insurance_certificate));
       
            $email                 =  $vehicleOwnerDetails['email'];
            $full_name            =  $vehicleOwnerDetails['full_name'];
            $route_url          = $link;

            $data = [
                'replaceData' => [$full_name, $route_url],
                'email' => $email, 'email_type' => 'release_certificate'
            ];

            try {
                dispatch(new SendEmailJob($data));
            } catch (\Exception $e) {
                $e->getMessage();
            }

        $insuranceRenewal->save();
        return response()->json([
            'success' => true,
            'message' => 'Certificate Released successfully',
        ]);
    }



    public function add()
    {

        return view('admin.insuranceRenewal.add');
    }

    public function edit(InsuranceRenewal $insuranceRenewal)
    {
        $model = $insuranceRenewal;
        return view('admin.insuranceRenewal.edit', compact('model'));
    }


    public function save(Request $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            InsuranceRenewal::create($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordAddedSpecific"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }

    public function Update(Request $request,  InsuranceRenewal $insuranceRenewal)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();

            $insuranceRenewal->update($input);
            DB::commit();
            return redirect()->route($this->listRoute)->with('success', $this->successName . __("messages.recordUpdated"));
        } catch (\Throwable $e) {
            DB::rollBack();
            CommonService::getExceptionError($e);
            return CommonService::redirectBackWithError($e);
        }
    }
}
