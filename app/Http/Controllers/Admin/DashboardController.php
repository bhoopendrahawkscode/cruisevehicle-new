<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CompanyInsuranceRenewal;
use App\Models\Community;
use App\Models\FuelRefill;
use App\Models\Blog;
use App\Models\Transaction;
use App\Constants\Constant;
use App\Http\Controllers\Admin\BaseController;
use App\Models\Vehicle;
use App\Services\MetaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use DB;

class DashboardController extends BaseController
{
    const ROLE_SLUG = 'roles.slug';
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->zeroZero = Constant::ZERO_ZERO;
        $this->fullFull = Constant::FULL_FULL;
        \View::share([
            'section' =>   __('messages.dashboard'),
        ]);
    }

    public function index(Request $request)
    {
        $searchData = $request->all();

    
        // Build the query for filtering data based on the search input
        $query = $this->getQuery($searchData);
    
        // Get the total count of users, sub-admins, and other statistics
        $totalUser = $this->getUserCount($query);
        $totalSubAdmin = $this->getSubAdminCount();
        $totalCommunities = Community::count();
        $totalBlogs = Blog::count();
        $totalEarnings = Transaction::sum('amount');
        $totalRegisteredVehicles = Vehicle::where($query)->count();
    
        // Retrieve company-specific requests and their statuses
        $companyId = Auth::user()->id;
        $totalRequest = CompanyInsuranceRenewal::where('company_id', $companyId)->where($query)->count();
        $totalDeclinedRequest = CompanyInsuranceRenewal::where(['company_id' => $companyId, 'status' => 2])->where($query)->count();
       $totalAcceptedRequest = CompanyInsuranceRenewal::where(['company_id' => $companyId, 'status' => 4])->where($query)->count();
  
        // Get the role of the authenticated user
        $roles = Auth::user()->roles;
        $roleName = $roles->pluck('slug')->first();
    
        // Generate graph data
       
        $graphData = [];
        $vehicle_data = Vehicle::where('status', 1)
            ->with(['CarModel', 'fuelType', 'engineCapacity', 'InsuranceCompany', 'brand', 'CoverType', 'transmissionType', 'user'])
            ->get()
            ->mapWithKeys(function ($vehicle) {
                $brandName = optional($vehicle->brand)->name;
                $modelName = optional($vehicle->CarModel)->name;
                $transmissionTypeName = optional($vehicle->transmissionType)->name;

                return [
                    $vehicle->id => trim("{$brandName} {$modelName} {$transmissionTypeName}"),
                ];
            })
            ->toArray();

            if(!empty($searchData['vehicle_id'])){
            if($searchData['vehicle_id']) 
            {

                $vehicle_id = $searchData['vehicle_id'];
                    $vehicledetail = Vehicle::where('id', $vehicle_id)->first();
                    $vehicleModelId=$vehicledetail->model_id;
                    $fuel_type=$vehicledetail->fuel_id;
                    $model_id = $vehicleModelId;
                    $other_model_id='';
                    if( $request->model_id=='same_model'){
                    $other_model_id=$vehicleModelId;
                    }

                    


                    $start_date = $request->input('from') ? Carbon::parse($request->input('from')) : Carbon::now()->subMonths(5)->startOfMonth();
                    $end_date = $request->input('to') ? Carbon::parse($request->input('to')) : Carbon::now()->endOfMonth();
                    $selectedVehicleEfficiencyData = $this->getSameVehicleModel($vehicle_id, $model_id, $start_date, $end_date,$fuel_type);
                    $otherVehiclesAverageEfficiencyData = $this->getOtherVehicleModel($vehicle_id, $other_model_id, $start_date, $end_date,$fuel_type);
                    $allMonths = [];
                    $currentMonth = $start_date->copy();
                    while ($currentMonth <= $end_date) {
                        $allMonths[] = $currentMonth->format('M'); 
                        $currentMonth->addMonth();
                    }
                    $formattedSelectedEfficiency = [];
                    $formattedOtherEfficiency = [];
                    foreach ($allMonths as $month) {
                        $formattedSelectedEfficiency[] = $selectedVehicleEfficiencyData[$month] ?? 0;
                        $formattedOtherEfficiency[] = $otherVehiclesAverageEfficiencyData[$month] ?? 0;
                    }
                  

                 $graphData['months'] = $allMonths;
                 $graphData['selectedVehicleMileage'] = $formattedSelectedEfficiency;
                 $graphData['otherVehiclesAverageMileage'] = $formattedOtherEfficiency;

                
            }
        }

        
        // If the role is admin or sub_admin, show the general admin dashboard
        if ($roleName == 'admin' || $roleName == 'sub_admin') {
            return view('admin.dashboard', compact(
                'totalUser',
                'totalSubAdmin',
                'searchData',
                'totalCommunities',
                'totalBlogs',
                'totalEarnings',
                'totalRegisteredVehicles',
                'graphData', // Pass graph data to the view
                'vehicle_data',
            ));
        } 
        // Otherwise, show the insurance-specific dashboard
        else {
            return view('admin.insurance_dashboard', compact(
                'totalUser',
                'totalSubAdmin',
                'searchData',
                'totalCommunities',
                'totalDeclinedRequest',
                'totalRequest',
                'totalAcceptedRequest',
                'graphData' // Pass graph data to the view
            ));
        }
    }
    
private function getQuery($searchData)
{
    $query = [
    ];

    if (isset($searchData['from']) && $searchData['from'] != '') {
        $query[] = ['created_at', '>=', $searchData['from'] . $this->zeroZero];
    }

    if (isset($searchData['to']) && $searchData['to'] != '') {
        $query[] = ['created_at', '<=', $searchData['to'] . $this->fullFull];
    }

    return $query;
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
public function getOtherVehicleModel($vehicle_id, $model_id, $start_date, $end_date, $fuel_type) {

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

    foreach ($flattenedResults as $month => $monthGroup) {
        if ($fuel_type != '10') {
            $fuel_c = $monthGroup->fuel;
            $a = isset($flattenedResults[$month + 1]) ? $flattenedResults[$month + 1]->fuel_refill_mileage : 0;
            $b = $monthGroup->fuel_refill_mileage ?? 0;

            $efficiencyByMonth[$monthGroup->fuel_refill_date] = ($a !== null && $b !== null && $a > $b) ? ($fuel_c * 100) / ($a - $b) : 0;
        } else {
            $efficiencyByMonth[$monthGroup->fuel_refill_date] = $monthGroup->efficiency_rate ?? 0;
        }
    }

    uksort($efficiencyByMonth, fn($c, $d) => strtotime($d) - strtotime($c));

    $groupedByMonth = [];
    foreach ($efficiencyByMonth as $date => $efficiency) {
        $month = Carbon::parse($date)->format('M');
        $groupedByMonth[$month][] = $efficiency;
    }

    return collect($groupedByMonth)->mapWithKeys(fn($efficiencyList, $month) => [$month => round(array_sum($efficiencyList) / count($efficiencyList), 2)])->toArray();
}


public function getOtherVehicleModel20_march_2025($vehicle_id, $model_id, $start_date, $end_date,$fuel_type){
        if($fuel_type!='10'){

            if($model_id=='all'){
                $results = FuelRefill::where('fuel_refill_date', '>=', Carbon::now()->subMonths(5))
                ->where('model_id', $model_id)
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

    private function getFuelEfficiencyGraph($vehicle_id, $model_id, $start_date, $end_date)
{
    // Query for the selected vehicle's efficiency
    $selectedVehicleEfficiency = DB::select("
        SELECT 
            MONTHNAME(f1.fuel_refill_date) AS month,
            AVG(f1.fuel * 100 / (f1.fuel_refill_mileage - f2.fuel_refill_mileage)) AS efficiency
        FROM fuel_refills f1
        LEFT JOIN fuel_refills f2 
            ON f1.vehicle_id = f2.vehicle_id 
            AND f1.fuel_refill_date > f2.fuel_refill_date
            AND NOT EXISTS (
                SELECT 1 FROM fuel_refills f3 
                WHERE f3.vehicle_id = f1.vehicle_id 
                AND f3.fuel_refill_date > f2.fuel_refill_date 
                AND f3.fuel_refill_date < f1.fuel_refill_date
            )
        WHERE f1.vehicle_id = ? 
          AND f1.fuel_refill_date BETWEEN ? AND ?
        GROUP BY MONTH(f1.fuel_refill_date)
        ORDER BY MONTH(f1.fuel_refill_date)
    ", [$vehicle_id, $start_date, $end_date]);

    // Query for other vehicles' average efficiency
    $queryParams = [$start_date, $end_date];

    if ($model_id !== 'all') {
        $vehicleModelId = DB::table('vehicles')->where('id', $vehicle_id)->value('model_id');
        $queryParams[] = $vehicleModelId;

        $otherVehiclesQuery = "
            SELECT 
                MONTHNAME(f1.fuel_refill_date) AS month,
                AVG(f1.fuel * 100 / (f1.fuel_refill_mileage - f2.fuel_refill_mileage)) AS efficiency
            FROM fuel_refills f1
            LEFT JOIN fuel_refills f2 
                ON f1.vehicle_id = f2.vehicle_id 
                AND f1.fuel_refill_date > f2.fuel_refill_date
                AND NOT EXISTS (
                    SELECT 1 FROM fuel_refills f3 
                    WHERE f3.vehicle_id = f1.vehicle_id 
                    AND f3.fuel_refill_date > f2.fuel_refill_date 
                    AND f3.fuel_refill_date < f1.fuel_refill_date
                )
            WHERE f1.vehicle_id != ?
              AND f1.fuel_refill_date BETWEEN ? AND ?
              AND f1.vehicle_id IN (
                  SELECT id FROM vehicles WHERE model_id = ?
              )
            GROUP BY MONTH(f1.fuel_refill_date)
            ORDER BY MONTH(f1.fuel_refill_date)
        ";
    } else {
        $otherVehiclesQuery = "
            SELECT 
                MONTHNAME(f1.fuel_refill_date) AS month,
                AVG(f1.fuel * 100 / (f1.fuel_refill_mileage - f2.fuel_refill_mileage)) AS efficiency
            FROM fuel_refills f1
            LEFT JOIN fuel_refills f2 
                ON f1.vehicle_id = f2.vehicle_id 
                AND f1.fuel_refill_date > f2.fuel_refill_date
                AND NOT EXISTS (
                    SELECT 1 FROM fuel_refills f3 
                    WHERE f3.vehicle_id = f1.vehicle_id 
                    AND f3.fuel_refill_date > f2.fuel_refill_date 
                    AND f3.fuel_refill_date < f1.fuel_refill_date
                )
            WHERE f1.vehicle_id != ?
              AND f1.fuel_refill_date BETWEEN ? AND ?
            GROUP BY MONTH(f1.fuel_refill_date)
            ORDER BY MONTH(f1.fuel_refill_date)
        ";
    }

    $otherVehiclesEfficiency = DB::select($otherVehiclesQuery, array_merge([$vehicle_id], $queryParams));

    // Prepare the graph data
    $months = [];
    for ($i = 5; $i >= 0; $i--) {
        $months[] = Carbon::now()->subMonths($i)->format('F');
    }

    $response = [
        "months" => $months,
        "selectedVehicleEfficiency" => $this->mapEfficiencyData($months, $selectedVehicleEfficiency),
        "otherVehiclesAverageEfficiency" => $this->mapEfficiencyData($months, $otherVehiclesEfficiency),
    ];

    return $response;
}

private function mapEfficiencyData($months, $efficiencyData)
{
   
    $mappedData = array_fill_keys($months, 0);

    foreach ($efficiencyData as $data) {
      
        $mappedData[$data->month] = round($data->efficiency, 2);
    }

    return array_values($mappedData);
}



}