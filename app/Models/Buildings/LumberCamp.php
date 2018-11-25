<?php

namespace App\Models\Buildings;

use App\Actions\AddResourceAction;
use App\Helpers\ClassSaturator;
use App\Models\Resources\Gold;
use App\Models\Resources\Stone;
use App\Models\Resources\Wood;

class LumberCamp extends Building
{


    protected $baseCost = [
        Wood::class => 100,
        Stone::class => 50,
        Gold::class => 40
    ];

    protected $baseGeneration = 10;

    protected $coefficient = 1.4;

    function buildActiveActions()
    {

    }

    function buildPassiveActions()
    {
        if(!$this->concrete) {
            return;
        }
        $this->passiveActions[AddResourceAction::class."_".Wood::class] = new AddResourceAction(
            ClassSaturator::instantiateClass(Wood::class),
            $this->baseGeneration + ceil(($this->entity->level - 1) * $this->coefficient * $this->baseGeneration)
        );
    }

}