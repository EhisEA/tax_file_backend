<?php

namespace Database\Seeders;

use App\Models\AccountantProfile;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        $users = User::all();
        foreach ($users as $user) {
            $user_profile = UserProfile::query()->where('user_id', '=', $user->id)->first();
            if ($user_profile) {
                $user_profile->user()->save($user);
            } else {
                $accountant_profile = AccountantProfile::query()->where('user_id', '=', $user->id)->first();
                if ($accountant_profile) {
                    $accountant_profile->user()->save($user);
                }
            }
        }
    }
}
