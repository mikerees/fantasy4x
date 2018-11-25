<?php


namespace App\Models\Buildings;


use App\Actions\AddResourceAction;
use App\Helpers\ClassSaturator;
use App\Models\Resources\Gold;
use App\Models\Resources\Research;
use App\Models\Resources\Stone;
use App\Models\Resources\Wood;

class Library extends Building
{

    protected $baseCost = [
        Wood::class => 200,
        Stone::class => 100,
        Gold::class => 80
    ];

    protected $baseGeneration = 4;

    protected $coefficient = 1.2;

    function buildActiveActions()
    {

    }

    function buildPassiveActions()
    {
        if(!$this->concrete) {
            return;
        }
        $this->passiveActions[AddResourceAction::class."_".Research::class] = new AddResourceAction(
            ClassSaturator::instantiateClass(Research::class),
            $this->baseGeneration + ceil(($this->entity->level - 1) * $this->coefficient * $this->baseGeneration)
            );
    }
}