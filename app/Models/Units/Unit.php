<?php


namespace App\Models\Units;


class Unit
{

    protected $entity;

    public function __construct()
    {
    }

    protected function saturate(\App\Entities\Spell $entity)
    {
        $this->entity = $entity;
    }

}