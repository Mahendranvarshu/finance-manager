<?php

namespace App\Listeners;

use App\Events\CollectorCreated;
use App\Mail\CollectorWelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Queueable;

    public function handle(CollectorCreated $event): void
    {
        $collector = $event->collector;

        // Send email in background
        Mail::to($collector->username)->send(new CollectorWelcomeMail($collector));
    }
}
