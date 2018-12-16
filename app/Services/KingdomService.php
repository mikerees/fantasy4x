<?php

namespace App\Services;

use App\Entities\Kingdom;
use App\Models\Kingdom as KingdomModel;
use App\Helpers\ClassSaturator;
use App\Models\Resources\Resource;

class KingdomService
{

    /**
     * Determine if the passed kingdom has the required resources passed in
     * TODO: refactor cost into a defined model
     *
     * @param KingdomModel $kingdom
     * @param array $cost a simple array of costs
     * @return bool
     */
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

    /**
     * Gift the passed kingdom some resources.
     *
     * @param KingdomModel $kingdom
     * @param string $resource class identifier of the resource amount
     * @param integer $amount
     */
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

    /**
     * Get the amount of passed resource the passed kingdom has
     *
     * @param Kingdom $kingdom
     * @param Resource $resource
     * @return int
     */
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

    /**
     * Get the kingdom currently used by the logged in user
     *
     * @return bool|KingdomModel
     */
    public function getAuthenticatedKingdom()
    {

        $user = auth()->user();
        if (!$user) {
            return false;
        }
        return ClassSaturator::getModel($user->kingdom);
    }

    /**
     * Get all kingdoms as domain models
     *
     * @return \Illuminate\Support\Collection[KingdomModel]
     */
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