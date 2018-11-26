<?php


namespace App\Models;


class Kingdom
{

    public $entity;

    protected $concrete = false;

    public function saturate(\App\Entities\Kingdom $entity)
    {
        $this->entity = $entity;
        $this->concrete = true;
    }

    public function refreshEntity()
    {
        if (!is_null($this->entity)) {
            $this->entity = $this->entity->fresh();
        }
    }

    public function getTickCount()
    {
        $this->refreshEntity();
        return $this->entity->ticks;
    }

    public function isConcrete()
    {
        if (!$this->concrete) {
            return false;
        }

        return true;
    }

    public function hasTicks()
    {
        if (!$this->isConcrete()) {
            return false;
        }

        if ($this->getTickCount() > 0) {
            return true;
        }

        return false;

    }

}