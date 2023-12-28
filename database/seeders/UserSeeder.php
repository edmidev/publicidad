<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password')
        ]);
        $super->assignRole(RoleEnum::SUPERADMIN->value);

        $users = User::factory(1)->create();
        foreach ($users as $user) {
            $user->assignRole(RoleEnum::ADMINISTRATOR->value);
        }

        $users = User::factory(5)->create();
        foreach ($users as $user) {
            $user->assignRole(RoleEnum::DOCTOR->value);
        }

        $users = User::factory(2)->create();
        foreach ($users as $user) {
            $user->assignRole(RoleEnum::RECEPSIONIST->value);
        }

        $users = User::factory(1)->create();
        foreach ($users as $user) {
            $user->assignRole(RoleEnum::DR_OZONO->value);
        }
    }
}
