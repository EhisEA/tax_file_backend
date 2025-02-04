<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Notifications\PasswordResetNotification;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserWithNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insertOrIgnore([
            "email" => "test_user_1234@example.com",
            "email_verified_at" => Carbon::now(),
            "password" => Hash::make("password"),
        ]);

        $user = User::whereEmail("test_user_1234@example.com")->first();

        $profile = UserProfile::query()->createOrFirst([
            "phone_number" => "08123456789",
            "first_name" => "Test",
            "last_name" => "Icles",
        ]);

        $user->profile()->associate($profile);
        $user->save();

        // for ($i = 1; $i <= 20; $i++) {
        //     $user->notify(new PasswordResetNotification());
        // }
    }
}
