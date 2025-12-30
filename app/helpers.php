<?php
use App\Http\Controllers\Admin\DropdownController;
use \App\Constants\Constant;
use Illuminate\Support\Facades\Cache;
use App\Services\GeneralService;
use Illuminate\Support\Facades\Storage;
if (!function_exists('loadDropDownRoutes')) {
    function loadDropDownRoutes()
    {
        ## Dropdown Routes

        $router = app()->make('router'); // PGupta
        $paths = [
            '/dropdown/index/{name}', '/dropdown/add/{name}', '/dropdown/save/{name}', '/dropdown/edit/{id}/{name}', '/dropdown/update/{id}/{name}', '/dropdown/status/{id}/{name}/{value}', '/dropdown/delete/{id}/{name}'
        ];
        foreach ($paths as $path) {
            $list = strtoupper(request()->segment(4));
            $list1 = strtoupper(request()->segment(5));

            $action = '';

            if (strpos($path, 'index') !== false) {
                $action = 'index';
            } elseif (strpos($path, 'add') !== false) {
                $action = 'add';
            } elseif (strpos($path, 'save') !== false) {
                $action = 'save';
            } elseif (strpos($path, 'edit') !== false) {
                $action = 'edit';
            } elseif (strpos($path, 'update') !== false) {
                $action = 'update';
            } elseif (strpos($path, 'status') !== false) {
                $action = 'status';
            } elseif (strpos($path, 'delete') !== false) {
                $action = 'delete';
            }

            switch ($action) {
                case 'index':
                    $router->any($path,[DropdownController::class, 'index'])
                        ->name('admin.dropdown.index')
                        ->middleware(Constant::CHECK_PERMISSION . $list . '_LIST');
                    break;

                case 'add':
                    $router->any($path,[DropdownController::class, 'add'])
                        ->name('admin.dropdown.add')
                        ->middleware(Constant::CHECK_PERMISSION . $list . '_ADD');
                    break;

                case 'save':
                    $router->post($path,[DropdownController::class, 'save'])
                        ->name('admin.dropdown.save')
                        ->middleware(Constant::CHECK_PERMISSION . $list . '_ADD');
                    break;

                case 'edit':
                    $router->any($path,[DropdownController::class, 'edit'])
                        ->name('admin.dropdown.edit')
                        ->middleware(Constant::CHECK_PERMISSION . $list1 . '_EDIT');
                    break;

                case 'update':
                    $router->post($path,[DropdownController::class, 'update'])
                        ->name('admin.dropdown.update')
                        ->middleware(Constant::CHECK_PERMISSION . $list1 . '_EDIT');
                    break;

                case 'status':
                    $router->any($path,[DropdownController::class, 'status'])
                        ->name('admin.dropdown.status')
                        ->middleware(Constant::CHECK_PERMISSION . $list1 . '_CHANGE_STATUS');
                    break;

                case 'delete':
                    $router->any($path,[DropdownController::class, 'delete'])
                        ->name('admin.dropdown.delete')
                        ->middleware(Constant::CHECK_PERMISSION . $list . '_DELETE');
                    break;

                default:
                    // Handle default case or throw an exception if needed
                    break;
            }
        }
    }
}

if (!function_exists('getSortIcon')) {
    function getSortIcon($sortBy = null, $order = null, $field = null)
    {
        if ($sortBy == $field && $order == 'desc') {
            echo '<i class="fas fa-sort-down"></i>';
        } elseif ($sortBy == $field && $order == 'asc') {
            echo '<i class="fas fa-sort-up"></i>';
        } else {
            echo '<i class="fas fa-sort"></i>';
        }
    }
}

if (!function_exists('link_to_route')) {
    function link_to_route($route = null, $name = null, $extras = null,$queryString=null)
    {
        parse_str($queryString, $queryString);

        $args   =   array_merge($queryString, [
            'sortBy' => $extras['sortBy'],
            'order' =>$extras['order'],
        ]);

        if(isset($extras['name'])){ // used in Dropdowns Controller
            $args['name'] = $extras['name'];
        }
        if(isset($extras['id'])){ // used in reportManager Controller
            $args['id'] = $extras['id'];
        }
        return Html::a(
            route($route,$args),
            $name
        );
    }
}

if (!function_exists('pr')) {
    function pr($array)
    {
        echo "<pre>";
        print_r($array);
    }
}


if (!function_exists('newOtp')) {
    function newOtp($digits = 4)
    {
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while ($i < $digits) {
            //generate a random number between 0 and 9.
            $pin .= random_int(0, 9);
            $i++;
        }
        return $pin;
    }
}




/**
 * Function is used to Active user role type 1 (User)
 *
 */
if (!function_exists('user_list')) {
    function user_list()
    {
        return DB::table('users')
            ->where('notification_status', 1)
            ->where('status', 1)
            ->where('role_id', 1)
            ->where('full_name', "!=", '')
            ->where('username', "!=", '')
            ->selectRaw('CONCAT(full_name," (",username,")") as full_name,id')
            ->orderBy('full_name', 'asc')
            ->pluck('full_name', 'id')->toArray();
    }
}


if (!function_exists('permissions_list')) {
    function permissions_list()
    {
        $res = [];
        $data = DB::table('permissions')
            ->select('id', 'name', 'group_name')
            ->orderBy('group_name', 'asc')
            ->get()
            ->groupBy('group_name');

        foreach ($data as $group_name => $permissions) {
            $res[$group_name] = $permissions->pluck('name', 'id')->toArray();
        }

        return $res;
    }
}




if (!function_exists('get_notification_count')) {

    function get_notification_count($userId = 0)
    {
        $get_notification_count     =   0;
        if (Cache::has('get_notification_count_'.$userId.'')) {

           $get_notification_count = Cache::get('get_notification_count_'.$userId.'');
        } else {
            $data = DB::table('user_notifications')
            ->select(DB::raw('count(*) as total'))
            ->where('status', 0)
            ->where('user_id', $userId)
            ->groupBy('user_id')
            ->first();
          if (isset($data->total)) {
             $get_notification_count=  Cache::put('get_notification_count_'.$userId.'', $data->total);
           }

        }
        return $get_notification_count=array('cache_value'=>$get_notification_count);
    }
}

if (!function_exists('addDateFilter')) {
    function addDateFilter($searchData, $searchVariable, $dateField, $DB)
    {
        if (isset($searchData['from']) && $searchData['from'] != '' && isset($searchData['to']) && $searchData['to'] != '') {
            $DB->where($dateField, '>=', $searchData['from'] . Constant::ZERO_ZERO);
            $DB->where($dateField, '<=', $searchData['to'] . Constant::FULL_FULL);
            $searchVariable =   array_merge($searchVariable, array('from' => $searchData['from']));
            $searchVariable =   array_merge($searchVariable, array('to' => $searchData['to']));
        } elseif (isset($searchData['from']) && $searchData['from'] != '') {
            $DB->where($dateField, '>=', $searchData['from'] . Constant::ZERO_ZERO);
            $searchVariable =   array_merge($searchVariable, array('from' => $searchData['from']));
        } elseif (isset($searchData['to']) && $searchData['to'] != '') {
            $DB->where($dateField, '<=', $searchData['to'] . Constant::FULL_FULL);
            $searchVariable =   array_merge($searchVariable, array('to' => $searchData['to']));
        }
        unset($searchData['display']);
        unset($searchData['_token']);
        unset($searchData['from']);
        unset($searchData['to']);
        if (isset($searchData['order'])) {
            unset($searchData['order']);
        }
        if (isset($searchData['sortBy'])) {
            unset($searchData['sortBy']);
        }
        if (isset($searchData['page'])) {
            unset($searchData['page']);
        }
        return array('searchData' => $searchData, 'searchVariable' => $searchVariable, 'DB' => $DB);
    }
}
if (!function_exists('addFiltersOr')) {
    function addFiltersOr($key, $val, $searchDataOr, $db)
    {
        $searchParamsOr            =    explode("-", substr($key, 1, -1));
        $searchParamsValuesOr        =    explode("-", substr($val, 1, -1));
        $db->where(function ($queryOr) use ($searchParamsOr, $searchParamsValuesOr, $searchDataOr) {
            foreach ($searchParamsOr as $keys => $values) {
                switch ($searchParamsValuesOr[$keys]) {
                    case 'like':
                        $queryOr->orWhere("$values", 'like', '%' . $searchDataOr['name'] . '%');
                        break;
                    case '=':
                        $queryOr->orWhere("$values", $searchDataOr['name']);
                        break;
                    default:
                }
            }
        });
        return $db;
    }
}
if (!function_exists('addFilters')) {
    function addFilters($searchData, $fieldsToSearch, $db)
    {

      
        foreach ($fieldsToSearch as $key => $val) {
            if (str_contains($key, '*') && isset($searchData['name']) && $searchData['name'] != '') {
                
                $db =  addFiltersOr($key, $val, $searchData, $db);
            } elseif (isset($searchData[$key]) && $searchData[$key] != '') {
                switch ($val) {
                    case 'like':
                      
                        $db->where("$key", "$val", '%' . $searchData[$key] . '%');
                        break;
                    case '=':
                       
                        if($key=='insurance_status'){
                        $db->where("company_insurance_renewals.status", $searchData[$key]);
                        }else{
                            $db->where("$key", $searchData[$key]);
                        }
                       

                        break;
                    case '!=':
                       
                        $db->where("$key", '!=',  $searchData[$key]);
                        break;
                    default:
                    
                }
            }
        }
        return $db;
    }
}


if (!function_exists('getFilters')) {
    /*
		$input is inputRequest
		$searchData is searchFormData
		$fieldsToSearch is array of fields which we need to search
		$DB is DB instance
		$dateField is field of date to be searched
	*/
    function getFilters($input, $request, $searchData, $fieldsToSearch, $DB, $dateField,$extraConditions=[])
    {

        $paginate = GeneralService::getSettings('pageLimit');
        if (isset($searchData['from']) && $searchData['from'] != '' && isset($searchData['to']) && $searchData['to'] != '') {
            $DB->where($dateField, '>=', $searchData['from'] . Constant::ZERO_ZERO);
            $DB->where($dateField, '<=', $searchData['to'] . Constant::FULL_FULL);
        } elseif (isset($searchData['from']) && $searchData['from'] != '') {
            $DB->where($dateField, '>=', $searchData['from'] . Constant::ZERO_ZERO);
        } elseif (isset($searchData['to']) && $searchData['to'] != '') {
            $DB->where($dateField, '<=', $searchData['to'] . Constant::FULL_FULL);
        }
        if(count($extraConditions) > 0){
            foreach ($extraConditions as $condition) {
                $DB->where($condition[0], $condition[1], $condition[2]);
            }
        }
        $DB = addFilters($searchData, $fieldsToSearch, $DB);
        $sortBy = ($request->sortBy) ? $request->sortBy : 'updated_at';
        $order  = ($request->order) ? $request->order  : 'DESC';
        $result = $DB->orderBy($sortBy, $order)->paginate($paginate);
        $complete_string        =   $input::query();
        unset($complete_string["sortBy"]);
        unset($complete_string["order"]);
        $query_string           =   http_build_query($complete_string);
        $result->appends($input::all())->render();
        return ['result' => $result, 'DB' => $DB, 'sortBy' => $sortBy, 'order' => $order, 'query_string' => $query_string];
    }
}

if (!function_exists('excerpt')) {
    function excerpt($text, $length = 20, $ending = '...') {
        // Trim the text to the specified length
        $text       =   strip_tags($text);
        $excerpt = substr($text, 0, $length);

        // Check if the text was truncated
        if (strlen($text) > $length) {
            // Find the position of the last space within the truncated text
            $last_space = strrpos($excerpt, ' ');

            // If a space was found, truncate the text at that position
            if ($last_space !== false) {
                $excerpt = substr($excerpt, 0, $last_space);
            }

            // Append the ending to the truncated text
            $excerpt .= $ending;
        }

        return $excerpt;
    }
}

if (!function_exists('getCommunityName')) {
    function getCommunityName($type = 0) {
        if(isset(Constant::COMMUNITIES[$type])){
            return Constant::COMMUNITIES[$type];
        }else{
            return '-';
        }
    }
}

if (!function_exists('getVideoCategoryName')) {
    function getVideoCategoryName($type = 0) {
        if(isset(Constant::VIDEO_CATEGORY[$type])){
            return Constant::VIDEO_CATEGORY[$type];
        }else{
            return '-';
        }
    }
}
