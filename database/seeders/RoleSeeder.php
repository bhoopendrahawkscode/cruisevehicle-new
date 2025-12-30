<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_data = [
            [
                'name' => 'user',
                'slug'=>'user',
            ],
            [
                'name' => 'admin',
                'slug' => 'admin',
            ],
            [
                'name' => 'sub admin',
                'slug' => 'sub admin',
            ]
            ];

            $permissions = Permission::pluck('id')->toArray();
            foreach ($role_data as $role_) {
                $role =Role::updateOrCreate(['name'=>$role_['name']],$role_);
                if($role_['name'] =='admin') {
                    Log::alert($role);
                    $role->permissions()->sync($permissions);
                }

            }
    }
}
