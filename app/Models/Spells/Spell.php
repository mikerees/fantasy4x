<?php


namespace App\Models\Spells;


class Spell
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