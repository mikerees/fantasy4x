<?php


namespace App\Actions;


abstract class Action
{

    public function __construct()
    {

    }

    /**
     * Execute all the steps added to the action
     */
    abstract function performAction();

}