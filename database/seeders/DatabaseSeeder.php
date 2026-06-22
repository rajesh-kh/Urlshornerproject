<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create roles
        $roles = ['SuperAdmin','Admin','Member','Sales','Manager'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // Create a SuperAdmin user using raw SQL as required
        $email = 'superadmin@example.com';
        $password = Hash::make('password');

        DB::statement('INSERT INTO users (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())', [
            'Super Admin', $email, $password,
        ]);

        $userId = DB::table('users')->where('email', $email)->value('id');
        $roleId = DB::table('roles')->where('name', 'SuperAdmin')->value('id');

        // assign role via raw SQL
        DB::statement('INSERT INTO role_user (user_id, role_id, created_at, updated_at) VALUES (?, ?, NOW(), NOW())', [$userId, $roleId]);
    }
}
