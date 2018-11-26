<?php


namespace App\Services;


use App\Entities\Kingdom;
use App\Helpers\ClassSaturator;
use App\Models\Buildings\Building;

class GameService
{

    /**
     * @var KingdomService
     */
    protected $kingdomService;

    /**
     * @var BuildingService
     */
    protected $buildingService;

    public function __construct(KingdomService $kingdomService, BuildingService $buildingService)
    {
        $this->kingdomService = $kingdomService;
        $this->buildingService = $buildingService;
    }

    public function processTurn()
    {
        $kingdoms = Kingdom::all();

        foreach ($kingdoms as $kingdom) {

            // process building actions first
            foreach ($kingdom->buildings as $building) {
                $buildingModel = ClassSaturator::getModel($building);
                $buildingModel->saturate($building);
                $actions = $buildingModel->getPassiveActions();
                foreach ($actions as $action) {
                    $action->performAction();
                }
            }

        }
    }

    /**
     * Process an action for a building. This costs 1 tick.
     *
     * @param Building $building
     * @return bool
     */
    public function processBuildingAction(Building $building)
    {
        $kingdom = $this->kingdomService->getAuthenticatedKingdom();

        if (!$kingdom) {
            return false;
        }

        if (!$kingdom->hasTicks()) {
            return false;
        }

        foreach ($building->getActiveActions() as $action) {
            $action->performAction();
        }

        return true;

    }

}