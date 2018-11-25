<?php


namespace App\Actions;


use App\Models\Resources\Resource;
use App\Services\KingdomService;

class AddResourceAction extends Action
{

    /**
     * @var Resource
     */
    protected $resource;

    /**
     * @var integer
     */
    protected $amount;

    public function __construct(Resource $resource, $amount)
    {
        $this->resource = $resource;
        $this->amount = $amount;
        parent::__construct();
    }

    public function performAction()
    {

        $kingdomService = app()->make(KingdomService::class);
        $kingdom = $kingdomService->getAuthenticatedKingdom();

        $kingdomService->giftResource($kingdom, get_class($this->resource), $this->amount);
    }

}