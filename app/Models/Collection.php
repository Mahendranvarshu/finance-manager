<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = [
        'party_id','collector_id','date','amount_collected',
        'remaining_amount','day_number','remarks'
    ];

    protected $casts = [
        'date' => 'date',
        'amount_collected' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function collector()
    {
        return $this->belongsTo(Collector::class);
    }
}
