<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'id' => RoleEnum::SUPERADMIN->value,
            'name' => 'Superadministrador'
        ]);

        Role::create([
            'id' => RoleEnum::ADMINISTRATOR->value,
            'name' => 'Administrador'
        ]);

        Role::create([
            'id' => RoleEnum::RECEPSIONIST->value,
            'name' => 'Recepcionista'
        ]);

        Role::create([
            'id' => RoleEnum::DOCTOR->value,
            'name' => 'Doctor'
        ]);


        Role::create([
            'id' => RoleEnum::DR_OZONO->value,
            'name' => 'Dr Ozono'
        ]);
    }
}
