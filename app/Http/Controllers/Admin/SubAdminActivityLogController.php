<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\GeneralService;
use Illuminate\Support\Facades\Request as Input;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

use function Laravel\Prompts\progress;

class SubAdminActivityLogController extends Controller
{
    protected $requiredMin2Max30;
    public function __construct()
    {

        $this->middleware('auth:admin');
        $version = GeneralService::getSettings('version');
        View::share([
            'version' => $version
        ]);
        $this->requiredMin2Max30 =  Config::get('constants.REQUIRED_MIN_2_MAX_30');
    }

    public function logList(Request $request)
    {

        $DB                            =   Activity::query();
        $fieldsToSearch              =   ['*log_name*' => '*like-like-like*'];
        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        return view('admin.actvitylogs.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

    public function delete(Activity $activity)
    {
        try {

            DB::beginTransaction();
            $activity->delete();
            DB::commit();
            return redirect()->back()->with(Constant::SUCCESS, __('messages.ActivityDeletedSuccessfully'));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
            return redirect()->back()->with(Constant::ERROR, trans("messages.somethingWentWrong"));
        }
    }

    public function view(Activity $activity)
    {
        $old = [];
        $new=[];
        $keys=[];
        $multi_lang_name = [];
        $multi_lang_attributes_keys = $multi_lang_keys =[];
        $multi_lang_properties=[];
        switch ($activity->event) {
            case 'Updated':
                $activity_by = trans('messages.updatedBy');
                $activity_on = trans('messages.updatedOn');

                break;
            case 'Deleted':
                $activity_by = trans('messages.deletedBy');
                $activity_on = trans('messages.deletedOn');

                break;
            default:
                $activity_by = trans('messages.CreatedBy');
                $activity_on = trans('messages.createdOn');
        }
        if ($activity->is_multi_language) {
            $multi_lang_properties = collect($activity->properties)->sort()->toArray();
            $multi_lang_attributes_keys = array_keys($multi_lang_properties['English']['old']??$multi_lang_properties['English']['new']);
            $multi_lang_keys = collect(array_keys($multi_lang_properties['English']))->sortDesc()->toArray();
        
            $multi_lang_name = array_keys($multi_lang_properties);
        } else {
            $properties = collect($activity->changes)->toArray();
            $new = $properties['attributes'] ?? [];
            if ($activity->event == 'Updated' || $activity->event == 'Deleted') {
                $old = $properties['old'] ?? [];
            }

            $keys = count($new) > 0 ? array_keys($new) : array_keys($old);
        }

        return view('admin.actvitylogs.view', compact('activity', 'new', 'old', 'keys', 'activity_by', 'activity_on','multi_lang_name','multi_lang_attributes_keys','multi_lang_properties','multi_lang_keys'));
    }
}
