<?php


namespace App\Actions;


abstract class Action
{

    public function __construct()
    {

    }

    /**
     * Apply resource to the authenticated kingdom
     */
    abstract function performAction();

}