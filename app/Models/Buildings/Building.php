<?php


namespace App\Models\Buildings;

use App\Actions\Action;

abstract class Building
{

    public $entity;

    protected $level;

    protected $activeActions;

    protected $passiveActions;

    protected $baseCost;

    protected $coefficient = 1;

    protected $concrete = false;

    public function __construct()
    {
        $this->activeActions = [];
        $this->passiveActions = [];
        $this->buildActiveActions();
        $this->buildPassiveActions();
    }

    public function saturate(\App\Entities\Building $entity)
    {
        $this->entity = $entity;
        $this->concrete = true;
        $this->buildPassiveActions();
        $this->buildActiveActions();
    }

    protected function buildActiveActions()
    {
    }

    protected function buildPassiveActions()
    {
    }

    public function getBaseCost()
    {
        return $this->baseCost;
    }

    public function getCoefficient()
    {
        return $this->coefficient;
    }

    /**
     * @return Action[]
     */
    public function getPassiveActions()
    {
        return $this->passiveActions;
    }

    /**
     * @return Action[]
     */
    public function getActiveActions()
    {
        return $this->activeActions;
    }

    public function getLevel()
    {
        return $this->entity->level;
    }

    public function getKingdomId()
    {
        return $this->entity->kingdom_id;
    }

    public function refreshEntity()
    {
        if (!is_null($this->entity)) {
            $this->entity = $this->entity->fresh();
        }
    }


}