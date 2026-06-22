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
        
        // Create companies
        $companyA = DB::table('companies')->insertGetId(['name' => 'Company A', 'created_at' => now(), 'updated_at' => now()]);
        $companyB = DB::table('companies')->insertGetId(['name' => 'Company B', 'created_at' => now(), 'updated_at' => now()]);

        // Create one user per role (except SuperAdmin already created)
        $seedUsers = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'Admin', 'company_id' => $companyA],
            ['name' => 'Member User', 'email' => 'member@example.com', 'role' => 'Member', 'company_id' => $companyA],
            ['name' => 'Sales User', 'email' => 'sales@example.com', 'role' => 'Sales', 'company_id' => $companyB],
            ['name' => 'Manager User', 'email' => 'manager@example.com', 'role' => 'Manager', 'company_id' => $companyB],
        ];

        foreach ($seedUsers as $su) {
            $uid = DB::table('users')->insertGetId([
                'name' => $su['name'],
                'email' => $su['email'],
                'password' => Hash::make('password'),
                'company_id' => $su['company_id'],
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $rid = DB::table('roles')->where('name', $su['role'])->value('id');
            DB::table('role_user')->insert(['user_id' => $uid, 'role_id' => $rid, 'created_at' => now(), 'updated_at' => now()]);
        }
    }
}
