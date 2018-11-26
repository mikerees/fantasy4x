<?php


namespace App\Helpers;


use Illuminate\Database\Eloquent\Model;

class ClassSaturator
{

    public static function instantiateClass($class)
    {
        return app()->make($class);
    }

    public static function getModel(Model $entity)
    {

        if (!isset($entity->class)) {
            throw new \InvalidArgumentException("Model passed is insatiable");
        }

        $model = self::instantiateClass($entity->class);

        $model->saturate($entity);

        return $model;
    }

}