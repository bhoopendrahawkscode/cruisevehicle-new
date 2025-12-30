<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'email' => 'admin@octal.com',
            'password' => '$2y$12$m5mGQLvgsrum6fH9xLk6D.kMiLC6q3i5LwEJJ1AqC5nJmq80Agbm6', //pass: Octal@123
            'first_name' => 'Admin',
            'last_name' => 'System Admin',
            'username' => 'octaladmin',
            'role_id' => 2,
        ];
        $admin = User::updateOrCreate($data);
        $role = Role::where('slug','admin')->first();

        if(!empty($admin->roles())){

            $admin->roles()->sync([$role->id]);

        }else{

            $admin->roles()->attach([$role->id]);
        }
        $permissions = $admin->roles->first()->permissions->pluck('id')->toArray();
        $admin->permissions()->sync($permissions);
    }
}
