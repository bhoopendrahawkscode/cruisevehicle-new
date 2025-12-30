<?php

namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Config, Auth, DB;
use App\Http\Requests\API\UpdateMobileNoRequest;
use App\Http\Requests\API\ChangePasswordRequest;
use App\Http\Requests\API\VerifyMobileOtp;
use App\Http\Requests\API\UpdateProfileRequest;
use App\Http\Requests\API\TrackMoodRequest;
use App\Http\Requests\API\FuelRefillApiRequest;
use App\Http\Requests\API\BlockUnBlockRequest;
use App\Http\Requests\API\VehicleApiRequest;
use App\Http\Requests\API\UserReportRequest;
use App\Http\Requests\API\ExpensesApiRequest;
use App\Http\Requests\API\ServiceApiRequest;
use App\Models\UsersOtp;
use App\Models\BlockUser;
use App\Models\ServiceType;
use App\Models\Banner;
use Carbon\Carbon;
use App\Models\InsuranceRenewal;
use App\Models\InsuranceCoverPeriod;
use App\Models\CompanyInsuranceRenewal;
use App\Models\Expense;
use App\Models\Service;
use App\Models\FuelRefill;
use App\Models\ServiceProvider;
use App\Models\Faq;
use App\Models\QuestionHistory;
use Illuminate\Validation\Rule;
use App\Models\UserReport;
use App\Models\User;
use App\Models\Cms;
use App\Models\CarModel;
use App\Models\InsuranceCoverType;
use App\Models\Brand;
use App\Models\ExpenseType;
use App\Models\Vehicle;
use App\Models\FuelType;
use App\Models\SupportComment;
use App\Models\TransmissionType;
use App\Models\EngineCapacity;
use App\Constants\Constant;
use App\Services\CommonService;
use App\Services\GeneralService;
use App\Models\UserNotification;
use App\Http\Resources\UserResource;
use App\Http\Resources\VehicleResource;
use App\Http\Resources\ExpensesApiResource;
use App\Http\Resources\CMSResource;
use App\Http\Resources\FuelRefillApiResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\BannerApiResource;
use App\Services\ImageService;
use App\Models\Question;
use App\Models\Video;
use App\Models\Audio;
use App\Models\Standout;
use App\Models\Quote;

use App\Http\Resources\QuestionResource;
use App\Http\Resources\VideoResource;
use App\Http\Resources\AudioResource;
use App\Http\Resources\GoodNewsResource;
use App\Http\Resources\ServiceProviderApiResource;
use App\Http\Resources\QuoteResource;
use App\Http\Resources\ServicesApiResource;
use App\Jobs\SendPushNotificationJob;
use App\Models\ContactDetail;
use App\Models\InsuranceQuoteConfirmation;
use App\Models\InsuranceQuoteReply;
use App\Models\Setting;
use App\Models\Support;
use App\Models\UserPermission;
use App\Http\Resources\InsuranceRenewalResource;
use Imagick;

class ApiController extends BaseController
{
    protected $paginate;
    protected $paginateAll;
    protected $message;
    protected $data;
    protected $noRecord;

    protected $dataListed;
    public function __construct()
    {
        $this->paginate              =       GeneralService::getSettings('pageLimit');
        $this->paginateAll           =       1000;
        $this->data                  =       new \stdClass;
        $this->message               =       __(Constant::ERROR_OCCURRED);
        $this->noRecord              =      trans('api.record_not_found');
        $this->dataListed            =      trans('messages.GET_DATA');
        define('SUCCESS_MESSAGE', trans('api.success'));

    }

    public function getVehicleDetail(Request $request)
    {

        $vehicle_id=$request->vehicle_id;

        try {
            $vehicle = Vehicle::with(['CarModel', 'fuelType', 'engineCapacity', 'InsuranceCompany', 'brand', 'CoverType', 'transmissionType','user'])
                              ->findOrFail($vehicle_id);

          return $this->sendResponse(new VehicleResource($vehicle,[]),trans('api.success'));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            return response()->json([
                'message' => "Vehicle with ID $vehicle_id not found.",
                'data' =>[],
                'status' => 404
            ], 404);
        }



    }
    public function getAllVehicles(Request $request)
    {

        $user_id=Auth::user()->id;
    
        try {
            $vehicle = Vehicle::with(['CarModel', 'fuelType', 'engineCapacity', 'InsuranceCompany', 'brand', 'CoverType', 'transmissionType','user'])
                              ->where('user_id', $user_id)->get();
          return $this->sendResponse(VehicleResource::collection($vehicle),trans('api.success'));
        } catch (\Throwable $e) {
           
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            return response()->json([
                'message' => "Vehicle not found.",
                'data' =>[],
                'status' => 404
            ], 404);
        }

    }


    public function getExpensesDetail(Request $request)
    {

        $expenses_id=$request->expenses_id;

        try {
            $expenses = Expense::with(['vehicle', 'ExpenseType','user'])
                              ->findOrFail($expenses_id);

          return $this->sendResponse(new ExpensesApiResource($expenses,[]),trans('api.success'));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            return response()->json([
                'message' => "Expense with ID $expenses_id not found.",
                'data' =>[],
                'status' => 404
            ], 404);
        }



    }
    public function getAllExpenses(Request $request)
    {
        $user_id = Auth::user()->id;

        try {
            $query = Expense::with(['vehicle', 'ExpenseType', 'user'])
                            ->where('user_id', $user_id);

            if ($request->filled('expenses_type_id')) { // Check if expenses_type_id is not empty
                $query->where('expenses_type_id', $request->expenses_type_id);
            }

            if ($request->filled('expenses_type_id')) { // Check if expenses_type_id is not empty
                $query->where('expenses_type_id', $request->expenses_type_id);
            }

            if ($request->filled('vehicle_id')) { // Check if vehicle_id is not empty
                $query->where('vehicle_id', $request->vehicle_id);
            }

            if ($request->filled('start_date')) { // Check if start_date is not empty
                $query->whereDate('expense_date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) { // Check if end_date is not empty
                $query->whereDate('expense_date', '<=', $request->end_date);
            }

            if ($request->filled('transmission_type')) {
                $query->whereHas('vehicle', function($query) use ($request) {
                    $query->where('transmission_type_id', $request->transmission_type);
                });
            }

            $expenses = $query->get();

            return $this->sendResponse(ExpensesApiResource::collection($expenses), trans('api.success'));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message = $e->getLine() . ' >> ' . $e->getMessage();
            }
            return response()->json([
                'message' => "Expenses not found.",
                'data' => [],
                'status' => 404
            ], 404);
        }
    }



    public function getAllServices(Request $request)
    {
        $user_id = Auth::user()->id;

        try {
            $query = Service::with(['vehicle', 'serviceProvider', 'user', 'serviceType'])->where('user_id', $user_id);

            if ($request->has('vehicle_id')) {
                $query->where('vehicle_id', $request->vehicle_id);
            }

            $expenses =  $query->orderBy('id', 'desc')->get();

            return $this->sendResponse(ServicesApiResource::collection($expenses), trans('api.success'));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message = $e->getLine() . ' >> ' . $e->getMessage();
            }
            return response()->json([
                'message' => "Services not found.",
                'data' => [],
                'status' => 404
            ], 404);
        }
    }

    public function deleteServices(Request $request)
    {
        $userId = Auth::user()->id;
        try {
            // Check if 'id' is passed in the request
            if ($request->has('id')) {
                // Delete service by ID for the logged-in user
                $service = Service::where('id', $request->id)
                                  ->where('user_id', $userId)
                                  ->first();

                if ($service) {
                    $service->delete();
                    return $this->sendResponse([], trans('Service has been deleted successfully.'));
                } else {
                    return $this->sendError('Service not found.');
                }
            } else {
                // Delete all services for the logged-in user if no 'id' is passed
                $services = Service::where('user_id', $userId);

                if ($services->exists()) {
                    $services->delete();
                    return $this->sendResponse([], trans('All services have been deleted successfully.'));
                } else {
                    return $this->sendError('No services found to delete.');
                }
            }
        } catch (\Throwable $e) {
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }

        return $this->sendError($this->message);
    }



    public function getAllFuelRefill(Request $request)
    {
        $user_id = Auth::user()->id;

        try {
            $query = FuelRefill::with(['vehicle', 'user','fuelType'])->where('user_id', $user_id);

            if ($request->filled('vehicle_id')) { // Use filled instead of has to ensure vehicle_id is not empty
                $query->where('vehicle_id', $request->vehicle_id);
            }

            if ($request->filled('start_date')) { // Same check for start_date
                $query->whereDate('fuel_refill_date', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) { // Same check for end_date
                $query->whereDate('fuel_refill_date', '<=', $request->end_date);
            }

            $expenses = $query->orderBy('id', 'desc')->get();

            return $this->sendResponse(FuelRefillApiResource::collection($expenses), trans('api.success'));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message = $e->getLine() . ' >> ' . $e->getMessage();
            }
            return response()->json([
                'message' => "Fuel Refill not found.",
                'data' =>  $this->message = $e->getLine() . ' >> ' . $e->getMessage(),
                'status' => 404
            ], 404);
        }
    }


    public function getFuelRefillGraphData(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'model_id' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);
    
        $vehicle_id = $request->vehicle_id;
        if (!$vehicle_id) {
            return $this->sendResponse($this->getEmptyGraphData(), "No vehicle selected.");
        }
        $vehicledetail = Vehicle::where('id', $vehicle_id)->first();
        $vehicleModelId=$vehicledetail->model_id;
        $fuel_type=$vehicledetail->fuel_id;
        
        
        $model_id = $request->model_id ?? $vehicleModelId;
        $other_model_id=$request->model_id;
        $start_date = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subMonths(5)->startOfMonth();
        $end_date = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
    
        
        $selectedVehicleEfficiencyData = $this->getSameVehicleModel($vehicle_id, $model_id, $start_date, $end_date,$fuel_type);

        $otherVehiclesAverageEfficiencyData = $this->getOtherVehicleModel($vehicle_id,$other_model_id , $start_date, $end_date,$fuel_type);
        $allMonths = [];
        $currentMonth = $start_date->copy();
        while ($currentMonth <= $end_date) {
            $allMonths[] = $currentMonth->format('M'); // Format as "Jan", "Feb", etc.
            $currentMonth->addMonth();
        }
        $formattedSelectedEfficiency = [];
        $formattedOtherEfficiency = [];
        foreach ($allMonths as $month) {
            $formattedSelectedEfficiency[] = $selectedVehicleEfficiencyData[$month] ?? 0;
            $formattedOtherEfficiency[] = $otherVehiclesAverageEfficiencyData[$month] ?? 0;
        }
    
        return $this->sendResponse([
            "months" => $allMonths,
            "selectedVehicleEfficiency" => $formattedSelectedEfficiency,
            "otherVehiclesAverageEfficiency" => $formattedOtherEfficiency
        ], "Graph data fetched successfully.");
    }
    
    public function getSameVehicleModel($vehicle_id, $model_id, $start_date, $end_date,$fuel_type){
            if($fuel_type!='10'){
            $results = FuelRefill::where('fuel_refill_date', '>=', Carbon::now()->subMonths(5))
                ->where('vehicle_id', $vehicle_id)
                ->where('model_id', $model_id)
                ->where('fuel_type', '!=', 10)
                ->where('status','1')
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->fuel_refill_date)->format('Y-m');
                })
                ->map(function($monthGroup) {
                    return $monthGroup->sortBy('fuel_refill_date');
                });

                $flattenedResults = $results->flatten();
                $efficiencyByMonth = [];
                foreach ($flattenedResults as $month => $monthGroup) {
                    $fuel_c = $monthGroup->fuel;
                  $a = isset($flattenedResults[$month + 1]) ? $flattenedResults[$month + 1]->fuel_refill_mileage : 0;
                  
                     $b = isset($monthGroup->fuel_refill_mileage) ? $monthGroup->fuel_refill_mileage : 0;
                    if ($a !== null && $b !== null && $a > $b) {
                        $efficiency_c = ($fuel_c * 100) / ($a - $b);
                        $efficiencyByMonth[$monthGroup->fuel_refill_date] = $efficiency_c;
                    } else {
                        $efficiencyByMonth[$monthGroup->fuel_refill_date] = 0; 
                    }
                }

              

            }else{
                $results = FuelRefill::where('fuel_refill_date', '>=', Carbon::now()->subMonths(5))
                ->where('vehicle_id', $vehicle_id)
                ->where('model_id', $model_id)
                ->where('fuel_type', $fuel_type)
                ->where('status','1')
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->fuel_refill_date)->format('Y-m');
                })
                ->map(function($monthGroup) {
                    return $monthGroup->sortBy('fuel_refill_date');
                });

                $flattenedResults = $results->flatten();
                $efficiencyByMonth = [];
                foreach ($flattenedResults as $month => $monthGroup) {
                    if(!empty($monthGroup->efficiency_rate)){
                        $efficiencyByMonth[$monthGroup->fuel_refill_date] = $monthGroup->efficiency_rate;
                    }else{
                        $efficiencyByMonth[$monthGroup->fuel_refill_date] = 0; 
                    }
                    
                }

            }
          
            $selectedVehicleEfficiency = [];

            uksort($efficiencyByMonth, function($c, $d) {
                return strtotime($d) - strtotime($c); 
            });
            $groupedByMonth = [];
            foreach ($efficiencyByMonth as $date => $efficiency) {
                $month = Carbon::parse($date)->format('M');
                $groupedByMonth[$month][] = $efficiency;
            }

            
            foreach ($groupedByMonth as $month => $efficiencyList) {
              
                $selectedVehicleEfficiency[$month] = round(array_sum($efficiencyList) / count($efficiencyList), 2);

            }

            return $selectedVehicleEfficiency;
    }
        public function getOtherVehicleModel($vehicle_id, $model_id, $start_date, $end_date, $fuel_type)
            {
              
                $query = FuelRefill::where('fuel_refill_date', '>=', Carbon::now()->subMonths(5));
                if (!empty($vehicle_id)) {
                    $query->whereNotIn('vehicle_id', (array) $vehicle_id);
                }
                if ($fuel_type != '10') {
                    $query->where('fuel_type', '!=', 10);
                    $query->where('status','1');
                } else {
                    $query->where('fuel_type', 10);
                    $query->where('status','1');
                }
                
                if (!empty($model_id)) {
                    $query->where('model_id', $model_id);
                }
                
                $results = $query->get()
                    ->groupBy(fn($date) => Carbon::parse($date->fuel_refill_date)->format('Y-m'))
                    ->map(fn($monthGroup) => $monthGroup->sortBy('fuel_refill_date'));

                $flattenedResults = $results->flatten();
                $efficiencyByMonth = [];

                foreach ($flattenedResults as $index => $entry) {
                    if ($fuel_type == '10') {
                        $efficiencyByMonth[$entry->fuel_refill_date] = $entry->efficiency_rate ?? 0;
                    } else {
                        $nextMileage = $flattenedResults[$index + 1]->fuel_refill_mileage ?? 0;
                        $currentMileage = $entry->fuel_refill_mileage ?? 0;
                        
                        if ($nextMileage > $currentMileage) {
                            $efficiencyByMonth[$entry->fuel_refill_date] = ($entry->fuel * 100) / ($nextMileage - $currentMileage);
                        } else {
                            $efficiencyByMonth[$entry->fuel_refill_date] = 0;
                        }
                    }
                }
                
                uksort($efficiencyByMonth, fn($a, $b) => strtotime($b) - strtotime($a));
                $groupedByMonth = [];
                foreach ($efficiencyByMonth as $date => $efficiency) {
                    $month = Carbon::parse($date)->format('M');
                    $groupedByMonth[$month][] = $efficiency;
                }
                
                $otherVehiclesAverageEfficiency = array_map(
                    fn($efficiencies) => round(array_sum($efficiencies) / count($efficiencies), 2),
                    $groupedByMonth
                );
                
                return $otherVehiclesAverageEfficiency;
            }


    public function getOtherVehicleModel_20_march_2025($vehicle_id, $model_id, $start_date, $end_date,$fuel_type){
            if($fuel_type!='10'){
                if($model_id=='all'){
                    $results = FuelRefill::where('fuel_refill_date', '>=', Carbon::now()->subMonths(5))
                    //->where('model_id', $model_id)
                    ->where('fuel_type', '!=', 10)
                    ->get()
                    ->groupBy(function($date) {
                        return Carbon::parse($date->fuel_refill_date)->format('Y-m');
                    })
                    ->map(function($monthGroup) {
                        return $monthGroup->sortBy('fuel_refill_date');
                    });

                }else{

                    $results = FuelRefill::where('fuel_refill_date', '>=', Carbon::now()->subMonths(5))
                    ->where('model_id', $model_id)
                    ->where('fuel_type', '!=', 10)
                    ->get()
                    ->groupBy(function($date) {
                        return Carbon::parse($date->fuel_refill_date)->format('Y-m');
                    })
                    ->map(function($monthGroup) {
                        return $monthGroup->sortBy('fuel_refill_date');
                    });
                }
            

                $flattenedResults = $results->flatten();
                $efficiencyByMonth = [];
    
                foreach ($flattenedResults as $month => $monthGroup) {
                    $fuel_c = $monthGroup->fuel;
                    $a = isset($flattenedResults[$month + 1]) ? $flattenedResults[$month + 1]->fuel_refill_mileage : 0;
                   
                     $b = isset($monthGroup->fuel_refill_mileage) ? $monthGroup->fuel_refill_mileage : 0;
                    if ($a !== null && $b !== null && $a > $b) {
                        $efficiency_c = ($fuel_c * 100) / ($a - $b);
                        $efficiencyByMonth[$monthGroup->fuel_refill_date] = $efficiency_c;
                    } else {
                        $efficiencyByMonth[$monthGroup->fuel_refill_date] = 0;
                    }
                }


            }else{
                $results = FuelRefill::where('fuel_refill_date', '>=', Carbon::now()->subMonths(5))
                ->where('model_id', $model_id)
                ->where('fuel_type', 10)
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->fuel_refill_date)->format('Y-m');
                })
                ->map(function($monthGroup) {
                    return $monthGroup->sortBy('fuel_refill_date');
                });

                $flattenedResults = $results->flatten();
                $efficiencyByMonth = [];
                foreach ($flattenedResults as $month => $monthGroup) {
                    if(!empty($monthGroup->efficiency_rate)){
                        $efficiencyByMonth[$monthGroup->fuel_refill_date] = $monthGroup->efficiency_rate;
                    }else{
                        $efficiencyByMonth[$monthGroup->fuel_refill_date] = 0; 
                    }
                    
                }


            }
        
            $otherVehiclesAverageEfficiency = [];

            uksort($efficiencyByMonth, function($c, $d) {
                return strtotime($d) - strtotime($c); 
            });
            $groupedByMonth = [];
            foreach ($efficiencyByMonth as $date => $efficiency) {
                $month = Carbon::parse($date)->format('M');
                $groupedByMonth[$month][] = $efficiency;
            }

            
            foreach ($groupedByMonth as $month => $efficiencyList) {
             $otherVehiclesAverageEfficiency[$month] = round(array_sum($efficiencyList) / count($efficiencyList), 2);
            }
            return $otherVehiclesAverageEfficiency;
    }

    

    private function getEmptyGraphData()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i)->format('M');
        }

        return [
            "months" => $months,
            "selectedVehicleEfficiency" => array_fill(0, 6, 0),
            "otherVehiclesAverageEfficiency" => array_fill(0, 6, 0),
        ];
    }


    public function getFuelRefillGraphData1(Request $request)
    {
            $request->validate([
                'vehicle_id' => 'nullable|exists:vehicles,id',
                'model_id' => 'nullable',
                'fuel_mileage' => 'boolean',
                'service_mileage' => 'boolean',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
            ]);

            $vehicle_id = $request->vehicle_id;
            $model_id = isset($request->model_id) ? $request->model_id:'all';
            $response = [];
            if (!empty($vehicle_id)) {
                if ($request->fuel_mileage) {
                    $response = $this->getMileageData($vehicle_id, $model_id, 'fuel_refills', 'fuel_refill_date', 'fuel_refill_mileage', $request->start_date, $request->end_date);
                }
                if ($request->service_mileage) {
                    $response = $this->getMileageData($vehicle_id, $model_id, 'services', 'service_date', 'service_cost', $request->start_date, $request->end_date);
                }
               } else {
                $response = $this->getEmptyMileageData($model_id, 'fuel_refills', 'fuel_refill_date', 'fuel_refill_mileage', $request->start_date, $request->end_date);

            }

            return $this->sendResponse($response, "Graph data fetched successfully");


    }

    private function getEmptyMileageData1($model_id, $table, $date_column, $mileage_column, $start_date = null, $end_date = null)
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $start_date = $start_date ? Carbon::parse($start_date) : $sixMonthsAgo;
        $end_date = $end_date ? Carbon::parse($end_date) : Carbon::now();

        // Get the last 6 months
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i)->format('F');
        }

        // Prepare response with zero values
        $response = [
            "months" => $months,
            "selectedVehicleMileage" => array_fill(0, 6, 0),
            "otherVehiclesAverageMileage" => array_fill(0, 6, 0)
        ];

        return $response;
    }

    private function getMileageData1($vehicle_id, $model_id, $table, $date_column, $mileage_column, $start_date = null, $end_date = null)
    {
        if ($model_id !== 'all') {
            $vehicleModelId = DB::table('vehicles')->where('id', $vehicle_id)->value('model_id');
        } else {
            $vehicleModelIds = DB::table('vehicles')->pluck('model_id')->toArray();
        }

        $sixMonthsAgo = Carbon::now()->subMonths(6);
        $start_date = $start_date ? Carbon::parse($start_date) : $sixMonthsAgo;
        $end_date = $end_date ? Carbon::parse($end_date) : Carbon::now();

        $selectedVehicleData = DB::table($table)
            ->select(DB::raw('MONTHNAME(' . $date_column . ') as month'), DB::raw('SUM(' . $mileage_column . ') as mileage'))
            ->where('vehicle_id', $vehicle_id)
            ->whereBetween($date_column, [$start_date, $end_date])
            ->groupBy(DB::raw('MONTH(' . $date_column . ')'))
            ->orderBy(DB::raw('MONTH(' . $date_column . ')'))
            ->get()
            ->keyBy('month');

        $otherVehiclesQuery = DB::table($table)
            ->select(DB::raw('MONTHNAME(' . $date_column . ') as month'), DB::raw('AVG(' . $mileage_column . ') as average_mileage'))
            ->where('vehicle_id', '!=', $vehicle_id)
            ->whereBetween($date_column, [$start_date, $end_date]);

        if ($model_id !== 'all') {
            $otherVehiclesQuery->whereIn('vehicle_id', function ($subQuery) use ($vehicleModelId) {
                $subQuery->select('id')->from('vehicles')->where('model_id', $vehicleModelId);
            });
        } else {
            if (!empty($vehicleModelIds)) {
                $otherVehiclesQuery->whereIn('vehicle_id', function ($subQuery) use ($vehicleModelIds) {
                    $subQuery->select('id')->from('vehicles')->whereIn('model_id', $vehicleModelIds);
                });
            }
        }

        $otherVehiclesData = $otherVehiclesQuery
            ->groupBy(DB::raw('MONTH(' . $date_column . ')'))
            ->orderBy(DB::raw('MONTH(' . $date_column . ')'))
            ->get()
            ->keyBy('month');

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i)->format('F');
        }

        $response = [
            "months" => $months,
            "selectedVehicleMileage" => [],
            "otherVehiclesAverageMileage" => []
        ];

        foreach ($months as $month) {
            $response['selectedVehicleMileage'][] = $selectedVehicleData[$month]->mileage ?? 0;
            $response['otherVehiclesAverageMileage'][] = $otherVehiclesData[$month]->average_mileage ?? 0;
        }

        return $response;
    }




    public function deleteUser(Request $request)
    {

        try {
            $userId = Auth::user()->id;
            $user=User::where('id', $userId)->first();
            Support::where('user_id', $userId)->delete();
            SupportComment::where('user_id', $userId)->delete();


            UserReport::where('user_id', $userId)
            ->orWhere('owner_id', $userId)
            ->delete();

            Vehicle::where('user_id', $userId)->delete();
            Expense::where('user_id', $userId)->delete();
            Service::where('user_id', $userId)->delete();
            FuelRefill::where('user_id', $userId)->delete();
            InsuranceQuoteConfirmation::where('user_id', $userId)->delete();
            InsuranceQuoteReply::where('user_id', $userId)->delete();
                $user->roles()->attach([1]);
            UserNotification::where('user_id', $userId)->delete();
            UserPermission::where('user_id', $userId)->delete();
            User::where('id', $userId)->delete();

            return $this->sendResponse($this->data,"Account deleted successfully.");
        } catch (\Throwable $e) {
            $this->message  =  CommonService::getExceptionError($e);
        }
        return $this->sendError($this->message);
    }


    public function addSupport(Request $request)
    {

        try {
            $support = new ContactDetail();
            $support->country_code = $request->country_code;
            $support->mobile_no = $request->mobile_no;
            $support->subject = $request->description;
            $support->message = $request->description;
            $support->name = $request->name;
            $support->email = $request->email;
            $support->user_id = Auth::user()->id;

            $support->save();

            return $this->sendResponse($support, 'Support added successfully.');
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

    public function getAllBanner(Request $request)
    {
        try {
                
       // $this->data = Banner::where('status','1')->get();
        $this->data = Banner::where('status', 1)->with(['banner_translations' => function($query) {
            $query->where('language_id', 1)->select('banner_id', 'language_id', 'title', 'banner_link');
        }])->get()->map(function($banner) {
            $translation = $banner->banner_translations->first(); 

            return [
                'id' => $banner->id,
                'type' => $banner->type,
                'image' => $banner->BannerImage,
                 'banner_link' => $translation->banner_link,
            ];
        });


        return $this->sendResponse($this->data,trans('api.success'));
      


        } catch (\Throwable $ex) {
            $this->message =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function addInsuranceRenewalRequest(Request $request)
    {
        try {

            $existingRequest = InsuranceRenewal::where('vehicle_id', $request->vehicle_id)
            ->where('user_id', Auth::user()->id)
            ->where('created_at', '>=', now()->subMonth())
            ->first();

        if ($existingRequest) {
            return $this->sendError('You cannot submit another insurance renewal request for this vehicle within 30 days of your last request.');
        }


            $renewalRequest = new InsuranceRenewal();
            $renewalRequest->vehicle_registered = $request->vehicle_registered;
            $renewalRequest->vehicle_id = $request->vehicle_id;
            $renewalRequest->vehicle_line = $request->vehicle_line;
            $renewalRequest->vehicle_disqualified = $request->vehicle_disqualified;
            $renewalRequest->vehicle_experience = $request->vehicle_experience;
            $renewalRequest->vehicle_accidents = $request->vehicle_accidents;
            $renewalRequest->vehicle_not_use = $request->vehicle_not_use;
            $renewalRequest->vehicle_drive_illness = $request->vehicle_drive_illness;
            $renewalRequest->vehicle_insurer = $request->vehicle_insurer;
            $renewalRequest->user_email = $request->email_id;
            $renewalRequest->nic = $request->nic;
            $renewalRequest->car_model = $request->car_model;
            $renewalRequest->year_of_manufacturer = $request->year_of_manufacturer;
            $renewalRequest->vehicle_registration_mark = $request->vehicle_registration_mark;
            $renewalRequest->value = $request->value;
            $renewalRequest->sum_to_be_insured = $request->sum_to_be_insured;
            $renewalRequest->cover_type = $request->cover_type;
            $renewalRequest->period_of_insurance_cover = $request->period_of_insurance_cover;
            $renewalRequest->insurance_renewal = $request->insurance_renewal;
            $renewalRequest->user_id = Auth::user()->id;
            $renewalRequest->full_name = Auth::user()->full_name;
            $renewalRequest->user_email = Auth::user()->email;

            $renewalRequest->save();
            $insuranceRenewalId = $renewalRequest->id;

            $companyNames = [];
            foreach ($request->company_id as $companyId) {
                // Fetch the company full_name from the User table
                $company = User::find($companyId);

                if ($company) {
                    $companyNames[] = $company->full_name; // Collect the company names

                    // Create a new CompanyInsuranceRenewal entry
                    CompanyInsuranceRenewal::create([
                        'company_id' => $companyId,
                        'insurance_renewal_id' => $insuranceRenewalId,
                        'user_id' => Auth::user()->id,
                    ]);

                        $adminDetails            =       User::getUserDetails($companyId);
                         $temp['type']            =      'UserInsuranceRenewal';
                         $temp['user_id']         =      $companyId;
                         $temp['link_id']         =      Auth::user()->id;
                         $temp['title']           =      "User Insurance Renewal Request.";
                         $temp['description']     =      "A user has been submitted Insurance Renewal Request. Please review and provide the Quote";
                         dispatch(new SendPushNotificationJob($adminDetails, $temp));

                }
            }


            $companyNamesList = implode(', ', $companyNames);


            $temp = [];
            $vehicleOwnerDetails = User::getUserDetails(Auth::user()->id); 
            $temp['type'] = 'InsuranceQuoteSubmitted';
            $temp['user_id'] = Auth::user()->id;
            $temp['link_id'] = Auth::user()->id;
            $temp['title'] = "Insurance Quote Submitted";
            $temp['description'] = "An insurance Renewal Request has been submitted  by you and Insurance Company will review this and let you know.";
           
            dispatch(new SendPushNotificationJob($vehicleOwnerDetails, $temp));


            return $this->sendResponse($renewalRequest, "Thank you for submitting to Insurance Company {$companyNamesList} a request for quote. Please stay tuned on your notifications to be kept informed of their proposals.");

        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }



    public function addExpenses(ExpensesApiRequest $request)
    {

        try {
            $expense = new Expense();
            $expense->expense_date = $request->expense_date;
            $expense->expenses_type_id = $request->expenses_type_id;
            $expense->vehicle_id = $request->vehicle_id;
            $expense->fuel = $request->fuel;
            $expense->mileage = $request->mileage;
            $expense->user_id = Auth::user()->id;
            $expense->cost = $request->cost;
            if ($request->hasFile('upload_receipt')) {
                $expense->upload_receipt = $request->file('upload_receipt')->store('upload_receipt');
            }

            $expense->save();

            return $this->sendResponse($expense, 'Expenses added successfully.');
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }



    public function addService(ServiceApiRequest $request)
    {

        try {
            $service = new Service();
            $service->service_date = $request->service_date;
            $service->service_mileage = $request->service_mileage;
            $service->vehicle_id = $request->vehicle_id;
            $service->service_provider_id = $request->service_provider_id;
            $service->service_type_id = $request->service_type_id;
            $service->user_id = Auth::user()->id;
            $service->service_cost = $request->service_cost;
            if ($request->hasFile('upload_receipt')) {
                $service->upload_receipt = $request->file('upload_receipt')->store('upload_receipt');
            }

            $service->save();

            $expense = new Expense();
        $expense->expense_date = $request->service_date;
        $expense->expenses_type_id = 2;
        $expense->vehicle_id = $request->vehicle_id;
        $expense->fuel = $request->fuel;
        $expense->mileage = $request->service_mileage;
        $expense->user_id = Auth::user()->id;
        $expense->cost = $request->service_cost;
        $expense->save();

            return $this->sendResponse($service, 'Service added successfully.');
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

    public function addFuelRefillold(FuelRefillApiRequest $request)
    {

        try {
            
            $currentUserId = auth()->user()->id;
            $vehicleId=$request->vehicle_id;
            $vehicleDetail = Vehicle::where('status', 1)->where('id', $vehicleId)
            ->with(['CarModel', 'fuelType', 'engineCapacity', 'InsuranceCompany', 'brand', 'CoverType', 'transmissionType', 'user'])->first();
            $FuelRefill = new FuelRefill();
            $FuelRefill->fuel_refill_date = $request->fuel_refill_date;
            $FuelRefill->fuel_refill_mileage = $request->fuel_refill_mileage;
            $FuelRefill->vehicle_id = $request->vehicle_id;
            $FuelRefill->model_id = $vehicleDetail->model_id;
            $FuelRefill->fuel_type = $vehicleDetail->fuel_id;
            $FuelRefill->efficiency_rate = $request->efficiency_rate;
            $FuelRefill->fuel = $request->fuel;
            $FuelRefill->user_id = Auth::user()->id;
            $FuelRefill->fuel_refill_cost = $request->fuel_refill_cost;

               $brandName = optional($vehicleDetail->brand)->name;
                $modelName = optional($vehicleDetail->CarModel)->name;
                $transmissionTypeName = optional($vehicleDetail->transmissionType)->name;

            $vehicle_name=  $brandName.' '.$modelName.' '.$transmissionTypeName;
         
            if ($request->hasFile('upload_receipt')) {
                $FuelRefill->upload_receipt = $request->file('upload_receipt')->store('upload_receipt');
            }

            $FuelRefill->save();

                $adminDetails = User::getUserDetails($currentUserId);
                $currentMileage = FuelRefill::where('vehicle_id', $vehicleId)
                ->orderBy('id', 'desc')
                ->value('fuel_refill_mileage');
            
            $nextServiceMileage = Service::where('vehicle_id', $vehicleId)
                ->where('user_id', $currentUserId)
                ->orderBy('id', 'desc')
                ->value('service_mileage');
            
            $thresholdMileage = $nextServiceMileage - 500; // Set the threshold 500 km before service
            
            if ($currentMileage >= $thresholdMileage) {
                $temp['type'] = 'MaintenanceMileageApproaching';
                $temp['user_id'] = $adminDetails['id'];
                $temp['link_id'] = Auth::user()->id;
                $temp['title'] = "Upcoming Maintenance Alert";
                $temp['description'] = "Your vehicle $vehicle_name is approaching its maintenance mileage. Please schedule a service soon. Current Mileage: $currentMileage, Next Service Mileage: $nextServiceMileage.";
            
                // Dispatch the notification job
                dispatch(new SendPushNotificationJob($adminDetails, $temp));
            }
            


            return $this->sendResponse($FuelRefill, 'Fuel Refill added successfully.');
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

    public function addFuelRefill(FuelRefillApiRequest $request)
{
    try {
        $currentUserId = auth()->user()->id;
        $vehicleId = $request->vehicle_id;

        $vehicleDetail = Vehicle::where('status', 1)
            ->where('id', $vehicleId)
            ->with([
                'CarModel', 'fuelType', 'engineCapacity', 
                'InsuranceCompany', 'brand', 'CoverType', 
                'transmissionType', 'user'
            ])
            ->first();

        if (!$vehicleDetail) {
            return $this->sendError('Vehicle not found.');
        }

        // Check if fuel_refill_id is provided for update
        $FuelRefill = $request->fuel_refill_id ? FuelRefill::find($request->fuel_refill_id) : new FuelRefill();

        if ($request->fuel_refill_id && !$FuelRefill) {
            return $this->sendError('Fuel refill record not found.');
        }

        $FuelRefill->fuel_refill_date = $request->fuel_refill_date;
        $FuelRefill->fuel_refill_mileage = $request->fuel_refill_mileage;
        $FuelRefill->vehicle_id = $request->vehicle_id;
        $FuelRefill->model_id = $vehicleDetail->model_id;
        $FuelRefill->fuel_type = $vehicleDetail->fuel_id;
        $FuelRefill->efficiency_rate = $request->efficiency_rate;
        $FuelRefill->fuel = $request->fuel;
        $FuelRefill->user_id = Auth::user()->id;
        $FuelRefill->fuel_refill_cost = $request->fuel_refill_cost;

        $brandName = optional($vehicleDetail->brand)->name;
        $modelName = optional($vehicleDetail->CarModel)->name;
        $transmissionTypeName = optional($vehicleDetail->transmissionType)->name;

        $vehicle_name = $brandName . ' ' . $modelName . ' ' . $transmissionTypeName;

        if ($request->hasFile('upload_receipt')) {
            $FuelRefill->upload_receipt = $request->file('upload_receipt')->store('upload_receipt');
        }

        $FuelRefill->save();

        $expense = new Expense();
        $expense->expense_date = $request->fuel_refill_date;
        $expense->expenses_type_id = 1;
        $expense->vehicle_id = $request->vehicle_id;
        $expense->fuel = $request->fuel;
        $expense->mileage = $request->fuel_refill_mileage;
        $expense->user_id = Auth::user()->id;
        $expense->cost = $request->fuel_refill_cost;
        $expense->save();

        // Fetch current and next service mileage
        $adminDetails = User::getUserDetails($currentUserId);
        $currentMileage = FuelRefill::where('vehicle_id', $vehicleId)
            ->orderBy('id', 'desc')
            ->value('fuel_refill_mileage');

        $nextServiceMileage = Service::where('vehicle_id', $vehicleId)
            ->where('user_id', $currentUserId)
            ->orderBy('id', 'desc')
            ->value('service_mileage');

        $thresholdMileage = $nextServiceMileage - 500; // Set threshold 500 km before service

        // Send a maintenance alert if needed
        if ($currentMileage >= $thresholdMileage) {
            $temp['type'] = 'MaintenanceMileageApproaching';
            $temp['user_id'] = $adminDetails['id'];
            $temp['link_id'] = Auth::user()->id;
            $temp['title'] = "Upcoming Maintenance Alert";
            $temp['description'] = "Your vehicle $vehicle_name is approaching its maintenance mileage. 
            Please schedule a service soon. Current Mileage: $currentMileage, Next Service Mileage: $nextServiceMileage.";

            dispatch(new SendPushNotificationJob($adminDetails, $temp));
        }

        $message = $request->fuel_refill_id ? 'Fuel Refill updated successfully.' : 'Fuel Refill added successfully.';
        return $this->sendResponse($FuelRefill, $message);

    } catch (\Throwable $e) {
        if (env('APP_DEBUG')) {
            $this->message = $e->getLine() . ' >> ' . $e->getMessage();
        }
        CommonService::createExceptionLog($e);
        return $this->sendError($this->message);
    }
}



    public function addVehicle(VehicleApiRequest $request)
    {
        try {
            $vehicle = $request->vehicle_id ? Vehicle::find($request->vehicle_id) : new Vehicle();
    
            if (!$vehicle) {
                return $this->sendError('Vehicle not found.');
            }
    
            $vehicle->owner_name = $request->owner_name;
            $vehicle->owner_address = $request->owner_address;
            $vehicle->street = $request->street;
            $vehicle->chassis_number = $request->chassis_number;
            $vehicle->user_id = Auth::user()->id;
            $vehicle->town = $request->town;
            $vehicle->reg_no = $request->reg_no;
            $vehicle->brand_id = $request->brand_id;
            $vehicle->model_id = $request->model_id;
            $vehicle->engine_capacity_id = $request->engine_capacity_id;
            $vehicle->fuel_id = $request->fuel_id;
            $vehicle->transmission_type_id = $request->transmission_type_id;
            $vehicle->renewal_period = $request->renewal_period;
            $vehicle->due_renewal_date = $request->due_renewal_date;
    
            // Handling file uploads
            if ($request->hasFile('road_tax_certificate')) {
                $vehicle->road_tax_certificate = $request->file('road_tax_certificate')->store('certificates');
            }
    
            $vehicle->insurance_company = $request->insurance_company;
            $vehicle->cover_type_id = $request->cover_type_id;
            $vehicle->premium = str_replace(',', '', $request->premium);
            $vehicle->mts_registered_date = $request->mts_registered_date;
            $vehicle->insurance_expiry_date = $request->insurance_expiry_date;
    
            if ($request->hasFile('fitness_certificate')) {
                $vehicle->fitness_certificate = $request->file('fitness_certificate')->store('certificates');
            }
    
            $vehicle->fitness_expiry_date = $request->fitness_expiry_date;
    
            $vehicle->save();
    
            $message = $request->vehicle_id ? 'Vehicle updated successfully.' : 'Vehicle added successfully.';
            return $this->sendResponse($vehicle, $message);
        } catch (\Throwable $e) {
            $logMessage = '[' . date('Y-m-d H:i:s') . '] Exception in ' . __METHOD__ . ' on line ' . $e->getLine() . ': ' . $e->getMessage() . PHP_EOL;
            file_put_contents(storage_path('logs/vehicle.log'), $logMessage, FILE_APPEND);
            
            if (env('APP_DEBUG')) {
                $this->message = $logMessage;
            }
            
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }
    
    public function addVehicle_old(VehicleApiRequest $request)
    {

        try {
            $vehicle = new Vehicle();
            $vehicle->owner_name = $request->owner_name;
            $vehicle->owner_address = $request->owner_address;
            $vehicle->street = $request->street;
            $vehicle->chassis_number = $request->chassis_number;
            $vehicle->user_id = Auth::user()->id;
            $vehicle->town = $request->town;
            $vehicle->reg_no = $request->reg_no;
            $vehicle->brand_id = $request->brand_id;
            $vehicle->model_id = $request->model_id;
            $vehicle->engine_capacity_id = $request->engine_capacity_id;
            $vehicle->fuel_id = $request->fuel_id;
            $vehicle->transmission_type_id = $request->transmission_type_id;
            $vehicle->renewal_period = $request->renewal_period;
            $vehicle->due_renewal_date = $request->due_renewal_date;
            // Handling file uploads
            if ($request->hasFile('road_tax_certificate')) {
                $vehicle->road_tax_certificate = $request->file('road_tax_certificate')->store('certificates');
            }
            $vehicle->insurance_company = $request->insurance_company;
            $vehicle->sum_assured_value = $request->sum_assured_value;
            $vehicle->cover_type_id = $request->cover_type_id;
            $vehicle->premium = $request->premium;
            $vehicle->mts_registered_date = $request->mts_registered_date;
            $vehicle->insurance_expiry_date = $request->insurance_expiry_date;

            if ($request->hasFile('fitness_certificate')) {
                $vehicle->fitness_certificate = $request->file('fitness_certificate')->store('certificates');
            }
            $vehicle->fitness_expiry_date = $request->fitness_expiry_date;

            $vehicle->save();

            return $this->sendResponse($vehicle, 'Vehicle added successfully.');
        } catch (\Throwable $e) {
            $logMessage = '[' . date('Y-m-d H:i:s') . '] Exception in ' . __METHOD__ . ' on line ' . $e->getLine() . ': ' . $e->getMessage() . PHP_EOL;
        file_put_contents(storage_path('logs/vehicle.log'), $logMessage, FILE_APPEND);
        if (env('APP_DEBUG')) {
            $this->message = $logMessage;
        }
        CommonService::createExceptionLog($e);
        return $this->sendError($this->message);
        }
    }


    public function getModels(Request $request)
    {
        $brand_id = $request->brand_id;
        $this->data = CarModel::where('brand_id', $brand_id)->where('status','1')->orderBy('name', 'asc')->get(['id','name']);

        return $this->sendResponse($this->data, trans('api.success'));
    }

    public function getInsurancePeriod(Request $request){

        $coverPeriod= InsuranceCoverPeriod::where('status','1')->get(['id','name']);

        return $this->sendResponse($coverPeriod, trans('api.success'));
    }

    public function getInsuranceCompany(Request $request)
    {
        $this->data = User::whereHas('roles', function ($q) {
            $q->where('roles.slug', 'insurance_manager');
        })
        ->where('status', 1)
        ->get(['id', 'full_name', 'insurance_company_employee_full_name']);
        

        return $this->sendResponse($this->data, trans('api.success'));
    }


    public function getEngineCapicity(Request $request)
    {
        $brand_id = $request->brand_id;
        $model_id = $request->model_id;
        $this->data = EngineCapacity::where('brand_id', $brand_id)->where('status','1')->where('model_id', $model_id)->get(['id','capacity']);

        return $this->sendResponse($this->data, trans('api.success'));
    }
    public function getEngineCapacityIdByName(Request $request)
    {
        $capacity_name = $request->capacity_name;
        $brandId = $request->brand_id;
        $model_id = $request->model_id;
        $capacity = EngineCapacity::where('capacity', 'like', '%' . $capacity_name . '%')->where('brand_id', $brandId)->where('model_id', $model_id)->where('status', '1')->first(['id', 'capacity']);
        if (!$capacity) {
            $newBrand = EngineCapacity::create([
                'capacity' => $capacity_name,
                'name' => $capacity_name,
                'brand_id' => $brandId,
                'model_id' => $model_id,
                'status' => '1',
            ]);
            $capacity = EngineCapacity::find($newBrand->id, ['id', 'capacity']);
        }
    
        return $this->sendResponse($capacity, trans('api.success'));
    }

    public function getModelIdByName(Request $request)
    {
        $modelName = $request->model_name;
        $brandId = $request->brand_id;
        $model = CarModel::where('name', 'like', '%' . $modelName . '%')->where('brand_id', $brandId)->where('status', '1')->first(['id', 'name']);
        if (!$model) {
            $newBrand = CarModel::create([
                'name' => $modelName,
                'brand_id' => $brandId,
                'status' => '1',
            ]);
            $model = CarModel::find($newBrand->id, ['id', 'name']);
        }
    
        return $this->sendResponse($model, trans('api.success'));
    }
    



    public function getBrands(Request $request)
    {

        $this->data = Brand::where('status','1')->where('status','1')->orderBy('name', 'asc')->get(['id','name']);

        return $this->sendResponse($this->data, trans('api.success'));
    }
    public function getServiceProviders(Request $request)
    {

        $this->data = ServiceProvider::where('status','1')->get();

        return $this->sendResponse(ServiceProviderApiResource::collection($this->data), trans('api.success'));
    }
    public function getServiceTypes(Request $request)
    {

        $this->data = ServiceType::where('status','1')->get(['id','name']);

        return $this->sendResponse($this->data, trans('api.success'));
    }
    public function getExpensesType(Request $request)
    {

        $this->data = ExpenseType::where('status','1')->get(['id','name']);

        return $this->sendResponse($this->data, trans('api.success'));
    }

    public function getFuelType(Request $request)
    {

        $this->data = FuelType::where('status','1')->get(['id','name','cost']);

        return $this->sendResponse($this->data, trans('api.success'));
    }
    public function getFaqs(Request $request)
    {

        $this->data = Faq::where('status', 1)->with(['faq_translations' => function($query) {
            $query->where('language_id', 1)->select('faq_id', 'language_id', 'question', 'answer');
        }])->get()->map(function($faq) {
            $translation = $faq->faq_translations->first(); // Get the first translation

            return [
                'id' => $translation->faq_id,
                'language_id' => $translation->language_id,
                'question' => $translation->question,
                'answer' => $translation->answer,
            ];
        });



        return $this->sendResponse($this->data,trans('api.success'));
    }

    public function getTransmissionType(Request $request)
    {

        $this->data = TransmissionType::where('status','1')->get(['id','name']);

        return $this->sendResponse($this->data, trans('api.success'));
    }

    public function getBrandIdByName(Request $request)
{
    $brandName = $request->brand_name;
    $brand = Brand::where('name', 'like', '%' . $brandName . '%')->where('status', '1')->first(['id', 'name']);
    if (!$brand) {
        $newBrand = Brand::create([
            'name' => $brandName,
            'status' => '1',
        ]);
        $brand = Brand::find($newBrand->id, ['id', 'name']);
    }

    return $this->sendResponse($brand, trans('api.success'));
}


    public function getCoverType(Request $request)
    {

        $this->data = InsuranceCoverType::where('status','1')->get(['id','name']);

        return $this->sendResponse($this->data, trans('api.success'));
    }

    public function updateMobileNo(UpdateMobileNoRequest $request)
    {
        try {

            $userId         =   Auth::user()->id;
            $alreadyExists  = User::where([
                'country_code' => $request->country_code, 'mobile_no' => $request->mobile_no
            ])->where('id', '!=', $userId)->first();
            if (!empty($alreadyExists)) {
                return $this->sendError(__("api.Mobile_No_Already_Exists"));
            }
            return $this->resendMobileOtp($request);
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

    public function verifyMobileOtp(VerifyMobileOtp $request)
    {
        try {

            $userId         =   Auth::user()->id;

            $alreadyExists  = UsersOtp::where(['mobile_no' =>  $request->country_code
                .$request->mobile_no, 'otp' => $request->otp])->where('expired_at','>=',time())->first();
            if (empty($alreadyExists)) {
                return $this->sendError(__("api.InvalidOtp"));
            }

            User::where('id', $userId)->update([
                'mobile_verified' => 1,
                'country_code' => $request->country_code, 'mobile_no' => $request->mobile_no
            ]);

            return $this->sendResponse($this->data, trans('api.success'));

        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }
    public function resendMobileOtp(Request $request)
    {
        try {
            $otp                            =       GeneralService::getGeneralOtp($request->country_code,
            $request->mobile_no);
            $this->data->otp                =    $otp;
            $this->data->country_code       =    $request->country_code;
            $this->data->mobile_no          =    $request->mobile_no;
            return $this->sendResponse($this->data, trans('api.success'));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }
    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            User::where('id', Auth::user()->id)->update(['password' => bcrypt($request->new_password)]);
            return $this->sendResponse($this->data, trans("messages.PASSWORD_CHANGED"));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

    public function updateNotificationStatus(Request $request)
    {
        try {
            User::where('id', Auth::user()->id)->update(['notification_status' =>$request->notification_status]);
            return $this->sendResponse($this->data, trans("Notification Status Updated Successfully"));
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message  = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }


    }
    public function updateProfile(Request $request)
    {
        $email = $request->email;
        $mobile_no = $request->country_code . $request->mobile_no;

        $email_auth = Auth::user()->email;
        $mobile_auth = Auth::user()->country_code . Auth::user()->mobile_no;
        $user = Auth::user();

        // if ($email_auth == $email && $mobile_auth == $mobile_no) {
        //     return $this->sendResponse($user, trans('api.PROFILE_UPDATED'));
        // }

        $validator = \Validator::make($request->all(), [
            'email' => [
                'nullable',
                'email',
                'min:2',
                'max:100',
                Rule::unique('users', 'email')->ignore(Auth::user()->id),
            ],
            'country_code' => [
                'required_with:mobile_no',
            ],
            'mobile_no' => [
                'nullable',
                'numeric',
                'digits_between:6,15',
                Rule::unique('users')->where(function ($query) use ($request) {
                    if ($request->filled('mobile_no')) {
                        return $query->where('country_code', $request->input('country_code'))
                                     ->where('mobile_no', $request->input('mobile_no'));
                    }
                })->ignore(Auth::user()->id),
            ],
        ]);

        $validator->after(function ($validator) use ($request, $email_auth, $mobile_auth) {
            $email = $request->email;
            $mobile_no = $request->country_code . $request->mobile_no;

            if (!$request->filled('email') && !$request->filled('mobile_no')) {
                $validator->errors()->add('email', 'At least one of email or mobile number must be provided.');
                $validator->errors()->add('mobile_no', 'At least one of email or mobile number must be provided.');
            }

            // Allow simultaneous updates for social requests
            if (!$request->social) {
                if ($email_auth == $email && $mobile_auth != $mobile_no) {
                    // Only mobile is changed
                } elseif ($email_auth != $email && $mobile_auth == $mobile_no) {
                    // Only email is changed
                } elseif ($email_auth != $email && $mobile_auth != $mobile_no) {
                    $validator->errors()->add('email', 'You cannot update both email and mobile number simultaneously.');
                    $validator->errors()->add('mobile_no', 'You cannot update both email and mobile number simultaneously.');
                }
            }
        });

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        DB::beginTransaction();
        try {
            $userId = Auth::user()->id;
            $user = User::find($userId);

            $fileName = '';
            if (!empty($request->image)) {
                $fileName = ImageService::fileUploadImage($request->image, $user->image, Config::get('constants.USER_FOLDER'));
                ImageService::manipulateImage(
                    Constant::OPERATION_TYPE,
                    $request->image,
                    Config::get('constants.USER_FOLDER'),
                    700,
                    700,
                    $fileName
                );
                $user->image = $fileName;
            }
            $user->country_code = $request->country_code;
            $user->mobile_no = $request->mobile_no;
            $user->email = $request->email;
            $user->full_name = $request->full_name;
            $user->device_token = $request->device_token;
            $user->device_token = $request->device_type;

            if ($email_auth != $email) {
                $user->email_verified = 0;
                $user->email_otp = 0;
            }
            $full_name=$request->full_name;
            $username = strtolower(str_replace(' ', '', $full_name));

            // Ensure the username is unique
            $originalUsername = $username;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $originalUsername . $counter;
                $counter++;
            }

            if ($request->social) {
                $user->mobile_verified = 1;
                $user->country_code = $request->country_code;
                $user->mobile_no = $request->mobile_no;
                $user->username = $username;
                $user->full_name = $request->full_name;
            }

            $user->save();
            DB::commit();

            return $this->sendResponse($user, trans('api.PROFILE_UPDATED'));
        } catch (\Throwable $e) {
            DB::rollback();
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function getProfile(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            if($request->user_id != ''){
                $userId  =   $request->user_id;
            }
            $user           =   User::where('id', $userId)->first();
            return $this->sendResponse(new UserResource($user,[]),trans('api.success'));
        } catch (\Throwable $e) {
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function searchUsers(Request $request)
    {
        try {
            $userId = Auth::user()->id;


            $allBlackedUsers  = BlockUser::where(['user_id1'=> $userId])->orWhere(['user_id2'=>$userId])->pluck('user_id1','user_id2');
            $query                  =       User::query();
            $allBlackedUsersNew     =       [];
            if(!empty($allBlackedUsers)){
                foreach($allBlackedUsers as $key=>$value){
                    $allBlackedUsersNew[] = $key;
                    $allBlackedUsersNew[] = $value;
                }
                $query->whereNotIn('id', $allBlackedUsersNew);
            }

            if($request->keyword != ''){
                $query->where(function ($query) use ($request) {
                    $query->where('username', 'like', '%' . $request->keyword . '%')
                          ->orWhere('full_name', 'like', '%' . $request->keyword . '%');
                });
            }

            $query->where(['role_id'=>1,'status'=>1])->where('username','!=','')->where('id','!=',$userId);
            $dataList = $query->paginate($this->paginate);
            return $this->sendResponse(UserResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $e) {
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function ocr_image1(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $image = $request->file('image');
        $fileName = time() . '_' . $image->getClientOriginalName();
        $image_folder = Config::get('constants.USER_FOLDER');
        $image->move(public_path($image_folder), $fileName);
        $imagePath = public_path($image_folder . '/' . $fileName);
        $tesseractPath = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe';
        $command = "\"$tesseractPath\" \"$imagePath\" stdout";
        $output = shell_exec($command);
        $words = preg_split('/\s+/', trim($output));
        $data = [
            'Make' => null,
            'Model' => null,
            'Engine_capacity' => null,
            'Fuel_used' => null,
            'Transmission_type' => null,
            'Register_Mts' => null,
        ];
    
        $fields = [
            'Make' => 2,
            'Model' => 2,
            'Engine_capacity' => ['Engine', 'capacity', 2],
            'Fuel_used' => ['Fuel', 'used', 2],
            'Transmission_type' => ['Transmission', 'Type', 2],
            'Register_Mts' => ['registered', 'in', 3],
        ];
        for ($index = 0; $index < count($words); $index++) {
            foreach ($fields as $key => $config) {
                if (is_array($config) && count($config) == 3) {
                    [$firstWord, $secondWord, $offset] = $config;
                    if (strcasecmp($words[$index], $firstWord) === 0
                        && isset($words[$index + 1])
                        && strcasecmp($words[$index + 1], $secondWord) === 0
                    ) {
                        if (isset($words[$index + $offset])) {
                          $data[$key] = str_replace(':', '', $words[$index + $offset]);;
                        }
                    }
                } elseif (strcasecmp($words[$index], $key) === 0 && isset($words[$index + $config])) {
                    $data[$key] = str_replace(':', '', $words[$index + $config]);
                }
            }
        }

        // foreach ($data as $key => $value) {
            
        //     if ($key == 'Engine_capacity' && is_null($value)) {
        //         return response()->json([
        //             'status' => 400,
        //             'message' => "Invalid Registration Passbook",
        //         ], 400);
        //     }
        // }
    
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $data,
        ]);
    }

    public function ocr_image(Request $request)
    {
        // $request->validate([
        //     'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        // ]);
        
        $image = $request->file('image');
        $fileName = time() . '_' . $image->getClientOriginalName();
        $image_folder = Config::get('constants.USER_FOLDER');
        $image->move(public_path($image_folder), $fileName);
        $imagePath = public_path($image_folder . '/' . $fileName);
        $tesseractPath = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe';
        $command = "\"$tesseractPath\" \"$imagePath\" stdout";
        $output = shell_exec($command);
        $words = preg_split('/\s+/', trim($output));
        
   // print_r( $words);die;
        // Initialize default data
        $data = [
            'Make' => null,
            'Model' => null,
            'Engine_capacity' => null,
            'Fuel_used' => null,
            'Transmission_type' => null,
            'Register_Mts' => null,
        ];
    
        // Extract Make, Model, and Engine capacity values from the OCR output
        $make = null;
        $model = null;
        $engine_capacity = null;
    
        foreach ($words as $index => $word) {
            if ($word === 'Make' && isset($words[$index + 1]) && $words[$index + 1] === ':') {
                $make = '';
                if (isset($words[$index + 2])) {
                    $make .= ' ' . $words[$index + 2];
                }
                for ($i = $index + 3; $i < count($words); $i++) {
                    if ($words[$i] === ':' || $words[$i] === '>:') {
                        break;
                    }
                    $make .= ' ' . $words[$i];  
                }
                $lastSpacePos = strrpos($make, ' ');
                if ($lastSpacePos !== false) {
                    $make = substr($make, 0, $lastSpacePos);
                }
                $data['Make'] = trim($make);  
            }
    
            if ($word === 'Model' && isset($words[$index + 1])) {
                $model = '';
                if (isset($words[$index + 2])) {
                    $model .= ' ' . $words[$index + 2];
                }
                for ($i = $index + 3; $i < count($words); $i++) {
                    if (strpos($words[$i], ':') === 0 || strpos($words[$i], '>:') === 0) {
                        break;
                    }
                    $model .= ' ' . $words[$i];
                }
                $lastSpacePos = strrpos($model, ' ');
                if ($lastSpacePos !== false) {
                    $model = substr($model, 0, $lastSpacePos);
                }
                $data['Model'] = trim($model);  
            }
    
            // Detect "Engine capacity" phrase and extract the capacity value
            if ($word === 'Engine' && isset($words[$index + 1]) && $words[$index + 1] === 'capacity') {
                $engine_capacity = '';
                if (isset($words[$index + 2])) {
                   $engine_capacity .= ' ' . $words[$index + 2];
                }

              if($words[$index + 2]==':' && isset($words[$index + 3])){
            
                $engine_capacity .= ' ' . $words[$index + 3];
              }
                
                $data['Engine_capacity'] = trim($engine_capacity, ': .');  
            }
            if ($word === 'Fuel' && isset($words[$index + 1]) && $words[$index + 1] === 'used') {
                $Fuel_used = '';
                if (isset($words[$index + 2])) {
                   $Fuel_used .= ' ' . $words[$index + 2];
                }
                if($words[$index + 2]==':' && isset($words[$index + 3])){
            
                    $Fuel_used .= ' ' . $words[$index + 3];
                  }
                $data['Fuel_used'] = trim($Fuel_used, ': .');  
            }

            if ($word === 'Transmission' && isset($words[$index + 1]) && $words[$index + 1] === 'Type') {
                $Transmission_type = '';
                if (isset($words[$index + 2])) {
                   $Transmission_type .= ' ' . $words[$index + 2];
                }
                if($words[$index + 2]==':' && isset($words[$index + 3])){
            
                    $Transmission_type .= ' ' . $words[$index + 3];
                  }
                $data['Transmission_type'] = trim($Transmission_type, ': .');  
            }

            if ($word === 'registered' && isset($words[$index + 1]) && $words[$index + 1] === 'in') {
               
                $Register_Mts = '';
                if (isset($words[$index + 3])) {
                   $Register_Mts .= ' ' . $words[$index + 3];
                }
               
                $data['Register_Mts'] = trim($Register_Mts, ': .');  
            }


        }
    
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $data,
        ]);
    }
    

    


    
    public function getCms(Request $request)
    {
      
        $reqData = $request->all();
        try {
            $languageId = CommonService::getLangIdFromLocale();
            $dataCMS = Cms::with(['cms_translation' => function ($q) use ($languageId) {
                $q->where(['language_id' => $languageId]);
            }])->where(['slug' => $reqData['slug']])->first();
            return $this->sendResponse(new CMSResource($dataCMS,[]),trans('api.success'));
        } catch (\Throwable $e) {
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function getContact(Request $request)
    {
        try {
            $this->data = Setting::get(['id','companyEmail','companyPhone','companyAddress','cost_per_liter_fuel']);
            return $this->sendResponse($this->data, trans('api.success'));
        } catch (\Throwable $e) {
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function getNotifications(Request $request)
    {
        $userId = Auth::user()->id;
        try {
            $dataList = UserNotification::where(['user_id' => $userId])->paginate($this->paginate);
            return $this->sendResponse(NotificationResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $e) {
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function getMoodQuestions(Request $request)
    {
        try {
            $dB                     =         new Question();
            $dB                     =         $dB->newQuery();
            $dB->leftJoin('question_translations', "questions.id", '=', "question_translations.question_id")
                ->select('question.*', 'question_translations.name')
                ->where("question_translations.language_id", '=', CommonService::getLangIdFromLocale())
                ->where(['status' => 1]);
            $sortBy                 =       'name';
            $orderBy                =       'asc';
            $dataList               =         $dB->select('*')
                ->orderBy($sortBy, $orderBy)
                ->paginate($this->paginate);
            return $this->sendResponse(QuestionResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getVideos(Request $request)
    {
        try {
            $dB                    =  Video::leftJoin('video_translations', "videos.id", '=',
                                        "video_translations.video_id")
                                        ->select('video.*', 'video_translations.name')
                                        ->where("video_translations.language_id", '=',
                                        CommonService::getLangIdFromLocale())
                                        ->where(['status' => 1]);
            $sortBy                 =       'name';
            $orderBy                =       'asc';
            $dataList               =         $dB->select('videos.id','video_translations.name',
            'video_translations.artist','videos.video',
            'videos.category','videos.duration')
                ->orderBy($sortBy, $orderBy)
                ->paginate($this->paginate);
            return $this->sendResponse(VideoResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getAudios(Request $request)
    {
        try {
            $dB                    =  Audio::leftJoin('audio_translations', "audios.id", '=',
                                        "audio_translations.audio_id")
                                        ->select('audio.*', 'audio_translations.name')
                                        ->where("audio_translations.language_id", '=',
                                        CommonService::getLangIdFromLocale())
                                        ->where(['status' => 1]);
            $sortBy                 =       'name';
            $orderBy                =       'asc';
            $dataList               =         $dB->select('audios.id','audio_translations.name',
            'audio_translations.artist','audios.audio','audios.image',
            'audios.duration')
                ->orderBy($sortBy, $orderBy)
                ->paginate($this->paginate);
            return $this->sendResponse(AudioResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getGoodNews(Request $request)
    {
        try {

            $dB                    =  Standout::leftJoin('standout_translations', "standouts.id", '=',
                                        "standout_translations.standout_id")
                                        ->select('standout.*', 'standout_translations.name')
                                        ->where("standout_translations.language_id", '=',
                                        CommonService::getLangIdFromLocale())
                                        ->where(['status' => 1]);
            $sortBy                 =       'name';
            $orderBy                =       'asc';
            $dataList               =         $dB->select('standouts.id','standout_translations.name',
            'standout_translations.description','standouts.image')
                ->orderBy($sortBy, $orderBy)
                ->paginate($this->paginate);
            return $this->sendResponse(GoodNewsResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function getQuotes(Request $request)
    {
        try {

            $currentDayOfWeek      = date('w');
            $daysToSubtract = $currentDayOfWeek - 1;
            $mondayDate = date('Y-m-d H:i:s', strtotime("-$daysToSubtract days"));
            $dB                    =  Quote::leftJoin('quote_translations', "quotes.id", '=',
                                        "quote_translations.quote_id")
                                        ->select('quote.*', 'quote_translations.name')
                                        ->where("quote_translations.language_id", '=',
                                        CommonService::getLangIdFromLocale())
                                        ->where(['status' => 1])->where('quotes.created_at','<',$mondayDate);
            $sortBy                 =       'day';
            $orderBy                =       'asc';
            $dataList               =         $dB->select('quotes.id','quotes.day','quote_translations.name',
            'quote_translations.written_by')
                ->orderBy($sortBy, $orderBy)
                ->paginate(100);
            return $this->sendResponse(QuoteResource::collection($dataList)
                ->response()->getData(), $this->dataListed);
        } catch (\Throwable $ex) {
            $this->message        =  $ex->getMessage();
        }
        return $this->sendError($this->message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function deleteNotification(Request $request)
    {

        try {
            $notification = UserNotification::find($request->id);
			if (!empty($notification)) {
                $this->message = 'Notification has been deleted';
                $notification->delete();
                return $this->sendResponse($this->data, trans('Notification Cleared successfully'));
            } else {
                $this->message = 'Notification not found';
                return $this->sendError($this->message);
            }
        } catch (\Throwable $e) {
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }

        return $this->sendError($this->message);

    }

    public function deleteAllNotification(Request $request)
    {
        $userId = Auth::user()->id;
        try {
            $notification = UserNotification::where('user_id',$userId);
			if (!empty($notification)) {
                $this->message = 'Notifications has been deleted';
                $notification->delete();
                return $this->sendResponse($this->data, trans('Notifications Cleared successfully'));
            } else {
                $this->message = 'Notification not found';
                return $this->sendError($this->message);
            }
        } catch (\Throwable $e) {
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }

        return $this->sendError($this->message);

    }

    public function trackMood(TrackMoodRequest $request)
    {
        DB::beginTransaction();
        try {

            $questionDetails           = Question::where('id', $request->question_id)->first();
            if (empty($questionDetails)) {
                return $this->sendError(__("api.QUESTION_DOES_NOT_EXISTS"));
            }

            $userId                    =   Auth::user()->id;
            $questionHistory           =   QuestionHistory::where(['question_id'=>$request->question_id,
            'user_id'=>$userId])->first();
            if($questionHistory == null){
                $questionHistoryNew = new QuestionHistory;
                $questionHistoryNew->question_id  =$request->question_id;
                $questionHistoryNew->user_id  =$userId;
                $questionHistoryNew->score  =$request->score;
                $questionHistoryNew->save();
            }else{
                $questionHistory->question_id  =$request->question_id;
                $questionHistory->user_id  =$userId;
                $questionHistory->score  =$request->score;
                $questionHistory->save();
            }

            $this->data->mood_score = QuestionHistory::updateMoodScore($userId);
            DB::commit();
            return $this->sendResponse($this->data, trans('api.MOOD_STATUS_UPDATED'));
        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message = __(Constant::ERROR_OCCURRED);
            if (env('APP_DEBUG')) {
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }

    public function reportUser(UserReportRequest $request)
    {

        DB::beginTransaction();
        try {
            $message2                       =       '';
            $userDetails                    =       User::where('id', $request->owner_id)->first();
            $userId                         =       Auth::user()->id;
            if (empty($userDetails) || $userDetails->user_id == $userId) {
                $message2 = 'Invalid Action';
            }
            $alreadyUserReport     =   UserReport::where(['owner_id'=> $request->owner_id,'user_id'=>$userId])->first();

            if (!empty($alreadyUserReport)) {
                $message2 = 'You already reported this user.';
            }
            if($message2 != ''){
                return $this->sendError($message2);
            }

            UserReport::create(['owner_id'=> $request->owner_id,'user_id'=>$userId,'type'=>$request->type,'message'=>$request->message]);

            $adminDetails            =       User::getUserDetails(1);
            $temp['type']            =      'UserReported';
            $temp['user_id']         =      $adminDetails['id'];
            $temp['link_id']         =      $userDetails->id;
            $temp['title']           =      "User Reported.";
            $temp['description']     =      "One of user reported another user (".$userDetails['full_name'].") ";
            dispatch(new SendPushNotificationJob($adminDetails, $temp));
            DB::commit();
            $this->message  =   "User Reported Successfully.";
            return $this->sendResponse($this->data,$this->message);

        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message  =  CommonService::getExceptionError($e);
        }
        return $this->sendError($this->message);
    }

    public function getBlockedUsers(Request $request)
    {
        DB::beginTransaction();
        try {

            $userId = Auth::user()->id;
            $allBlackedUsers2  = BlockUser::where(['user_id1'=> $userId])->pluck('user_id2','user_id2');
            $query                  =       User::query();
            $query->whereIn('id', $allBlackedUsers2);
            $query->whereNotIn('id',[$userId]);
            $dataList = $query->paginate($this->paginate);
            return $this->sendResponse(UserResource::collection($dataList)
                ->response()->getData(), $this->dataListed);

        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message = __(Constant::ERROR_OCCURRED);
            if(env('APP_DEBUG')){
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }
    public function blockUnblockUser(BlockUnBlockRequest $request)
    {

        DB::beginTransaction();
        try {

            $userId                         =       Auth::user()->id;

            BlockUser::where(['user_id1'=> $userId,'user_id2'=>$request->user_id])->delete();
            if($request->type == 'BLOCK'){
                $this->message  =   "User blocked Successfully.";
                BlockUser::create(['user_id1'=> $userId,'user_id2'=>$request->user_id]);
            }else{
                $this->message  =   "User unblocked Successfully.";
            }
            DB::commit();
            return $this->sendResponse($this->data,$this->message);

        } catch (\Throwable $e) {
            DB::rollback();
            DB::commit();
            $this->message = __(Constant::ERROR_OCCURRED);
            if(env('APP_DEBUG')){
                $this->message  = $e->getMessage();
            }
            CommonService::createExceptionLog($e);
        }
        return $this->sendError($this->message);
    }
    
    /**
     * Get the Insurance Renewal Request.
     *  
     * @author Karmpal(octal) Created_at 20240910
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function getInsuranceRenewal(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $vehicle_id = $request->vehicle_id;
    
            // Check if any record has status 3 or 4
            $hasStatusThreeOrFour = CompanyInsuranceRenewal::where('user_id', $user_id)
                ->when($vehicle_id, function ($query, $vehicle_id) {
                    $query->whereHas('insuranceRenewal', function ($subQuery) use ($vehicle_id) {
                        $subQuery->where('vehicle_id', $vehicle_id);
                    });
                })
                ->whereIn('status', [3, 4])
                ->exists();
    
            // If status 3 or 4 exists, fetch only those
            $renewalRequests = CompanyInsuranceRenewal::with('insuranceRenewal')
                ->where('user_id', $user_id)
                ->when($vehicle_id, function ($query, $vehicle_id) {
                    $query->whereHas('insuranceRenewal', function ($subQuery) use ($vehicle_id) {
                        $subQuery->where('vehicle_id', $vehicle_id);
                    });
                })
                ->when($hasStatusThreeOrFour, function ($query) {
                    $query->whereIn('status', [3, 4]);
                })
                ->orderBy('id', 'desc')
                ->get();
    
            return $this->sendResponse(InsuranceRenewalResource::collection($renewalRequests), trans('api.getInsuranceRenewal'));
    
        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }
    
    

    /**
     * For Insurance Renewal Proceed.
     *  
     * @author Karmpal(octal) Created_at 20240911
     * @param  { 'id':11,'comment:''}
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */
    public function InsuranceRenewalProceed(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'id' => 'required|integer|exists:company_insurance_renewals,id',
                'comment' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors()->first(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
            $id = $request->id;
            $comment = $request->comment;
            $user_id = Auth::user()->id;

            $CompanyInsuranceRenewal = CompanyInsuranceRenewal::where('id',$id)
                ->where('user_id',$user_id)
                ->where('status',1)
                ->first();

            if (!$CompanyInsuranceRenewal) {
                return $this->sendError(trans('api.InsuranceRenewalNotFoundOrInvalidStatus'), JsonResponse::HTTP_NOT_FOUND);
            }

            $CompanyInsuranceRenewal->status = 3;
            $CompanyInsuranceRenewal->comment = $comment;
            if (!empty($request->attachment)) {
                $fileName = ImageService::fileUploadImage($request->attachment, $CompanyInsuranceRenewal->attachment, Config::get('constants.USER_FOLDER'));
                $CompanyInsuranceRenewal->attachment = $fileName;
            }
            $CompanyInsuranceRenewal->save();

            InsuranceRenewal::where('id', $CompanyInsuranceRenewal->insurance_renewal_id)
            ->update(['status' => 1]);

            $temp = [];
            $vehicleOwnerDetails = User::getUserDetails(Auth::user()->id); 
            $temp['type'] = 'InsuranceRequestRenewed';
            $temp['user_id'] = Auth::user()->id;
            $temp['link_id'] = Auth::user()->id;
            $temp['title'] = "Insurance has Renewed";
            $temp['description'] = "insurance has been renewed and the certificate shall be sent to you shortly";
           
            dispatch(new SendPushNotificationJob($vehicleOwnerDetails, $temp));


            $temp = [];
            $vehicleCompanyDetail = User::getUserDetails($CompanyInsuranceRenewal->company_id); 
            $temp['type'] = 'InsuranceRequestRenewed';
            $temp['user_id'] = $CompanyInsuranceRenewal->company_id;
            $temp['link_id'] = Auth::user()->id;
            $temp['title'] = "Insurance has Renewed";
            $temp['description'] = "Insurance has been processed by the user " . Auth::user()->full_name . ". Please review and respond.";
            dispatch(new SendPushNotificationJob($vehicleCompanyDetail, $temp));
            

            return $this->sendResponse((object)[], trans('api.InsuranceRenewalProceed'), JsonResponse::HTTP_OK);

        } catch (\Throwable $e) {
            if (env('APP_DEBUG')) {
                $this->message = $e->getLine() . ' >> ' . $e->getMessage();
            }
            CommonService::createExceptionLog($e);
            return $this->sendError($this->message);
        }
    }

}
