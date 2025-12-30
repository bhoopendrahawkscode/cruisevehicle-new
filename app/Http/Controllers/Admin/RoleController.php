<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends BaseController
{
    protected $requiredMin2Max30,$somethingWentWrongMsg;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:admin');
        $this->somethingWentWrongMsg = trans('messages.somethingWentWrong');
        $this->requiredMin2Max30 =  Config::get('constants.REQUIRED_MIN_2_MAX_30');
    }

    public function roleList(Request $request)
    {
        $DB                            =   Role::query();
        $fieldsToSearch              =   ['*name*' => '*like-like-like*'];
        $searchVariable                =    $request->all();
        $output                     =    getFilters(new Input, $request, $searchVariable, $fieldsToSearch, $DB, 'created_at');
        extract($output);
        return view('admin.role.index', compact('result', 'searchVariable', 'sortBy', 'order', 'query_string'));
    }

    public function addRole()
    {
        return view('admin.role.add');
    }

    public function editRole(Role $role)
    {

        return  view('admin.role.edit', compact('role'));
    }

    public function saveRole(Request $request)
    {
        try {
            $input = $request->only('name');
            $validator = Validator::make(
                $input,
                [
                    'name' => 'min:4|max:30|unique:roles,name'
                ],
                [
                    'name.unique' => trans('messages.uniqueRole')
                ]
            );
            if ($validator->fails()) {
                $validator->messages();
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $input['slug'] = $request->name;
            DB::beginTransaction();
            $role = Role::create($input);

            if (!empty($request->permissions) && count($request->permissions) > 0) {

                $role->permissions()->attach($request->permissions);
            }

            DB::commit();

            return redirect()->route('admin.role.list')->with('success', trans("messages.roleAdded"));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', trans("messages.somethingWentWrong"));
        }
    }

    public function updateRole(Request $request, Role $role)
    {
        try {
            $input = $request->only('name');
            $validator = Validator::make(
                $input,
                [
                    'name' => ['min:4', 'max:30', Rule::unique('roles', 'name')->ignore($role->id)]
                ],
                [
                    'name.unique' => trans('messages.uniqueRole')
                ]
            );
            if ($validator->fails()) {
                $validator->messages();
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $input['slug'] = $request->name;
            DB::beginTransaction();
            $role->update($input);
            $users = User::whereHas('roles', function ($q) use ($role) {
                $q->where('roles.id', $role->id);
            })->get();

            if (!empty($request->permissions) && count($request->permissions) > 0) {

                $role->permissions()->sync($request->permissions);
                if (!empty($users)) {
                    foreach ($users as $user) {
                        $user->permissions()->sync($request->permissions);
                    }
                }
            } else {
                $role->permissions()->detach();
                if (!empty($users)) {
                    foreach ($users as $user) {
                        $user->permissions()->detach();
                    }
                }
            }
            DB::commit();
            return redirect()->route('admin.role.list')->with(Constant::SUCCESS, trans("messages.roleUpdated"));
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->back()->withInput()->with(Constant::ERROR, $this->somethingWentWrongMsg);
        }
    }

    public function validateRoleName(Request $request)
    {
        if ($request->name) {
            $query = Role::where('name', $request->name);
            if ($request->id) {
                $query->where('id', '!=', $request->id);
            }
            $role = $query->first();
            if ($role) {
                return "false";
            } else {
                return "true";
            }
        }
        return "true";
    }
}
