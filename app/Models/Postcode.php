<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{
    protected $fillable = [
        'pcd',
        'lat',
        'long',
    ];

    public $timestamps = false;
}

