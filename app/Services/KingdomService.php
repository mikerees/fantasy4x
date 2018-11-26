<?php

namespace App\Services;

use App\Entities\Kingdom;
use App\Models\Kingdom as KingdomModel;
use App\Helpers\ClassSaturator;
use App\Models\Resources\Resource;

class KingdomService
{

    public function hasResources(KingdomModel $kingdom, $cost)
    {
        foreach ($cost as $class => $val) {
            $resources = $kingdom->entity->resources;
            $found = false;
            foreach ($resources as $resource) {

                if ($resource->class == $class) {
                    $found = true;
                    if ($resource->amount < $val) {
                        return false;
                    }
                    break;
                }
            }
            if ($found == false) {

                return false;
            }
        }
        return true;
    }

    public function giftResource(KingdomModel $kingdom, $resource, $amount)
    {
        $resource = $kingdom->entity->resources()->where("class", $resource)->firstOrCreate([
            "amount" => 0,
            "kingdom_id" => $kingdom->entity->id,
            "class" => $resource
        ]);

        $resource->amount += $amount;
        $resource->save();
    }

    public function getResourceAmount(Kingdom $kingdom, Resource $resource)
    {


        foreach ($kingdom->resources as $kingdomResource) {
            if (get_class($resource) == $kingdomResource->class) {
                $model = ClassSaturator::getModel($kingdomResource);
                return $model->getStockpiledAmount();
            }
        }

        return 0;
    }

    public function getAuthenticatedKingdom()
    {

        $user = auth()->user();
        if (!$user) {
            return false;
        }
        return ClassSaturator::getModel($user->kingdom);
    }

    public function getKingdoms()
    {
        $kingdoms = Kingdom::all();
        $return = collect([]);

        foreach ($kingdoms as $kingdom) {
            $return->push(ClassSaturator::getModel($kingdom));
        }

        return $return;
    }

}