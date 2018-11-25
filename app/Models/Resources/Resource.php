<?php


namespace App\Models\Resources;


class Resource
{

    public $entity;

    protected $entityClass;

    public function __construct()
    {
        $this->entityClass = \App\Entities\Resource::class;
    }

    public function saturate(\App\Entities\Resource $entity)
    {
        $this->entity = $entity;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function getStockpiledAmount()
    {
        return $this->entity->amount;
    }

}