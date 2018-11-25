<?php

namespace App\Services;

use App\Entities\Kingdom;
use App\Helpers\ClassSaturator;
use App\Models\Resources\Resource;

class KingdomService
{

    public function hasResources(Kingdom $kingdom, $cost)
    {
        foreach ($cost as $class => $val) {
            $resources = $kingdom->resources;
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

    public function giftResource(Kingdom $kingdom, $resource, $amount)
    {
        $resource = $kingdom->resources()->where("class", $resource)->firstOrCreate([
            "amount" => 0,
            "kingdom_id" => $kingdom->id,
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
        return $user->kingdom;
    }

}