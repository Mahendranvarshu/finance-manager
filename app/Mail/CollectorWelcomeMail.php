<?php

namespace App\Mail;

use App\Models\Collector;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CollectorWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $collector;

    public function __construct(Collector $collector)
    {
        $this->collector = $collector;
    }

    public function build()
    {
        return $this->subject('Welcome to Our System')
                    ->markdown('emails.collector.welcome');
    }
}
