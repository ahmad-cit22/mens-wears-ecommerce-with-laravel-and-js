<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', 'sadeknurul5@gmail.com')->first();
        if (is_null($user)) {
          $user = new User();

          $user->type = 1;
          $user->name = "Sadek Nurul";
          $user->username = "sadeknurul";
          $user->email = "sadeknurul5@gmail.com";
          $user->phone = "01763100517";
          $user->address = "Dhaka, Bangladesh";
          $user->password = Hash::make("12345678");
          $user->save();

          $role = Role::create(['name' => 'Admin']);
          $permissions = Permission::all()->pluck('name')->toArray();
          if (!empty($permissions)) {
            $role->syncPermissions($permissions);
          }

          $user->syncRoles($role);
          
        }
    }
}
