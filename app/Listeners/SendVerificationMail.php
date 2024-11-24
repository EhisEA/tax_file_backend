<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\Registered;
use App\Mail\EmailVerification;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationMail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $verification_code = VerificationCode::query()->create([
            'code' => mt_rand(1000, 9999),
            'user_id' => $event->user->id,
            'used_at' => null,
            'expires_at' => Carbon::now()->addHour(),
        ]);

        Mail::to($event->user)->send(new EmailVerification($verification_code));
    }
}
