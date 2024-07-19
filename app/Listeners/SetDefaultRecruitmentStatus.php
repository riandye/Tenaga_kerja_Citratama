<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\recruitment;

class SetDefaultRecruitmentStatus
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
    public function handle(UserCreated $event)
    {
        $user = $event->user;

        // Set default status recruitment
        $recruitment = new Recruitment();
        $recruitment->ID_user = $user->ID_user;
        $recruitment->status = 'tersedia'; // Status default yang Anda inginkan
        $recruitment->save();
    }
}
