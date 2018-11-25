<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Kingdom extends Model
{

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

}
