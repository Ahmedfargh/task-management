<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data
        Task::query()->delete();
        User::query()->delete();
        Tenant::query()->delete();

        // Create 3 Tenants
        $tenants = Tenant::factory(3)->create();

        foreach ($tenants as $tenant) {
            // Create 3 Users for each Tenant
            $users = User::factory(3)->create([
                'tenant_id' => $tenant->id,
            ]);

            foreach ($users as $user) {
                // Create 5 Tasks for each User
                Task::factory(5)->create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $user->id,
                ]);
            }
        }

        // Create a specific Test User
        $testTenant = Tenant::factory()->create(['name' => 'Demo Org', 'id' => 'demo']);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'tenant_id' => $testTenant->id,
        ]);
    }
}
