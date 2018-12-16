<?php

namespace App\Services;

use App\Entities\Building;
use App\Models\Kingdom;
use App\Exceptions\Building\CannotUpgradeException;
use App\Helpers\ClassSaturator;
use App\Models\Buildings\Library;
use App\Models\Buildings\Building as BuildingModel;

class BuildingService
{


    /**
     * @var KingdomService
     */
    protected $kingdomService;

    /**
     * BuildingService constructor.
     * Injects the kingdom service as most if not all of these methods are Kingdom specific.
     * If we ever need information on a kingdom use this.
     *
     * @param KingdomService $kingdomService
     */
    public function __construct(KingdomService $kingdomService)
    {
        $this->kingdomService = $kingdomService;
    }

    /**
     * Construct a library for the passed kingdom.
     *
     * @param Kingdom $kingdom
     * @return Building
     * @throws CannotUpgradeException
     * @deprecated There's a generic method now, please use that. This is an artefact of TDD
     * @see constructBuilding();
     */
    public function constructLibrary(Kingdom $kingdom)
    {
        return $this->constructBuilding($kingdom, Library::class);
    }

    /**
     * @param Kingdom $kingdom the kingdom to construct the building for
     * @param $buildingClass string the Game Model class of the building to construct
     * @return Building
     * @throws CannotUpgradeException
     */
    public function constructBuilding(Kingdom $kingdom, $buildingClass)
    {
        $model = ClassSaturator::instantiateClass($buildingClass);

        // don't proceed if the building has already been constructed
        if ($this->hasBuilding($kingdom, $buildingClass)) {
            throw new CannotUpgradeException("You have already constructed this building.");
        }

        // if we can afford/"level" the building, go ahead and build it.
        if ($this->canLevel($kingdom, $model)) {
            $building = new Building();
            $building->kingdom_id = $kingdom->entity->id;
            $building->level = 1;
            $building->class = get_class($model);
            $building->save();
            return ClassSaturator::getModel($building);
        } else {
            throw new CannotUpgradeException("You do not have enough resources to construct!");
        }
    }

    /**
     * Get the library.
     *
     * @param Kingdom $kingdom
     * @return mixed
     * @deprecated This is an artefact of TDD
     * @see getBuilding()
     */
    public function getLibrary(Kingdom $kingdom)
    {
        return $this->getBuilding($kingdom, Library::class);
    }


    /**
     * Retrieve the kingdom's building of the passed Game Model class
     *
     * @param Kingdom $kingdom
     * @param $buildingClass
     * @return mixed
     */
    public function getBuilding(Kingdom $kingdom, $buildingClass)
    {
        $kingdom->refreshEntity();
        foreach ($kingdom->entity->buildings as $building) {
            if ($building->class == $buildingClass) {
                return ClassSaturator::getModel($building);
            }
        }
    }

    /**
     * Level up the passed building.
     *
     * @param Kingdom $kingdom
     * @param BuildingModel $model
     * @throws CannotUpgradeException
     */
    public function levelUp(Kingdom $kingdom, BuildingModel &$model)
    {
        // If the kingdom can afford to level up the building, go ahead and do this.
        if ($this->canLevel($kingdom, $model)) {
            $model->entity->level++;
            $model->entity->save();
        } else {
            throw new CannotUpgradeException("You do not have enough resources to upgrade!");
        }
    }

    /**
     * Check to see if the passed kingdom can level up the passed building.
     * Technically we could get the kingdom from the building model. Don't ask me why I did
     * it this way, I coded it yesterday.
     *
     * @param Kingdom $kingdom
     * @param BuildingModel $building
     * @return bool
     */
    public function canLevel(Kingdom $kingdom, BuildingModel $building)
    {
        $cost = $this->getUpgradeCost($building);

        // use the kingdom service to check if the kingdom has enough resources
        if ($this->kingdomService->hasResources($kingdom, $cost)) {
            return true;
        }
        return false;
    }

    /**
     * Get the cost to upgrade the building from its current level.
     * The maths behind this is ONLY DEFINED HERE. If in future you want to change
     * the way costs scale, use this method. Make sure it stays an exponential curve though.
     *
     * @param BuildingModel $building
     * @return array
     */
    public function getUpgradeCost(BuildingModel $building)
    {
        $baseCost = $building->getBaseCost();
        $building->refreshEntity();

        $cost = [];

        if (!is_null($building->entity)) {
            foreach ($baseCost as $class => $val) {
                $cost[$class] = $val + ceil($building->entity->level * $building->getCoefficient() * $building->getCoefficient() * $val);
            }
            return $cost;
        }
        return $baseCost;
    }

    /**
     * Check to see if a kingdom has a specific type of building. Pass in a kingdom model
     * and a string representing the class of building to check for the presence of.
     *
     * @param Kingdom $kingdom
     * @param $buildingClass
     * @return bool
     */
    public function hasBuilding(Kingdom $kingdom, $buildingClass)
    {
        foreach ($kingdom->entity->buildings as $building) {
            if ($building->class == $buildingClass) {
                return true;
            }
        }
        return false;
    }

}