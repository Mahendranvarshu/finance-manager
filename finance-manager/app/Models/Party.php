<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = [
        'dl_no','name','store_name','phone','address',
        'loan_amount','interest_amount','daily_amount',
        'total_days','starting_date','ending_date',
        'collector_id','status'
    ];

    protected $casts = [
        'starting_date' => 'date',
        'ending_date' => 'date',
        'loan_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'daily_amount' => 'decimal:2',
    ];

    public function collector()
    {
        return $this->belongsTo(Collector::class);
    }

    public function collections()
    {
        return $this->hasMany(Collection::class);
    }
}

