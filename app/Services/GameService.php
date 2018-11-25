<?php


namespace App\Services;


use App\Entities\Kingdom;
use App\Helpers\ClassSaturator;

class GameService
{

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

}