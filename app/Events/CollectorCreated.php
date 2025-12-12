<?php

namespace App\Events;

use App\Models\Collector;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CollectorCreated
{
    use Dispatchable, SerializesModels;

    public $collector;

    public function __construct(Collector $collector)
    {
        $this->collector = $collector;
    }
}
