<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arealine extends Model
{
    use HasFactory;

    protected $table = "area_lines";

    protected $fillable = [
        'area_name',
        'image',
        'route_name',
        'start_point',
        'end_point',
        'distance_km',
        'priority',
        'status'
    ];
}
