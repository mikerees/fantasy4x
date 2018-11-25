<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        "kingdom_id",
        "amount",
        "class"
    ];
}
