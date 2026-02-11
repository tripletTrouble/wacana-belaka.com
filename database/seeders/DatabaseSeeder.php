<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Deri Prasetyo',
            'email' => 'deri.mbois@gmail.com',
            'password' => bcrypt('1'),
            'email_verified_at' => now(),
        ]);

        // Seed roles
        $this->seedRoles();

        // Assign role to user
        $user->assignRole(RoleEnum::ADMIN->value);
    }

    protected function seedRoles(): void
    {
        foreach (RoleEnum::cases() as $role) {
            \Spatie\Permission\Models\Role::create(['name' => $role->value]);
        }
    }
}
