<?php

namespace Tests\Feature;

use App\Entities\Building;
use App\Exceptions\Building\CannotUpgradeException;
use App\Helpers\ClassSaturator;
use App\Models\Buildings\Library;
use App\Models\Buildings\LumberCamp;
use App\Models\Resources\Gold;
use App\Models\Resources\Research;
use App\Models\Resources\Stone;
use App\Models\Resources\Wood;
use App\Services\BuildingService;
use App\Services\GameService;
use App\Services\KingdomService;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BuildingManagementTest extends TestCase
{

    use RefreshDatabase;

    protected $user;
    protected $kingdom;
    /**
     * @var BuildingService
     */
    protected $buildingService;
    /**
     * @var KingdomService
     */
    protected $kingdomService;

    /**
     * @var GameService
     */
    protected $gameService;

    protected function setUp()
    {
        parent::setUp();
        $this->buildingService = app()->make(BuildingService::class);
        $this->kingdomService = app()->make(KingdomService::class);
        $this->gameService = app()->make(GameService::class);
        $this->user = factory('App\User')->create();
        $this->kingdom = factory('App\Entities\Kingdom')->create(['user_id' => $this->user->id]);
    }

    /** @test */
    function a_user_cannot_construct_a_library_without_required_resources()
    {
        $this->expectException(CannotUpgradeException::class);
        $this->buildingService->constructLibrary($this->kingdom);
    }

    /** @test */
    function a_user_can_construct_a_library()
    {
        $this->kingdomService->giftResource($this->kingdom, Wood::class, 10000);
        $this->kingdomService->giftResource($this->kingdom, Stone::class, 10000);
        $this->kingdomService->giftResource($this->kingdom, Gold::class, 10000);
        $this->buildingService->constructLibrary($this->kingdom);
        $this->assertInstanceOf(Library::class, $this->buildingService->getBuilding($this->kingdom, Library::class));
        $this->assertEquals($this->buildingService->getLibrary($this->kingdom)->entity->kingdom_id, $this->kingdom->id);
        $this->assertEquals($this->buildingService->getLibrary($this->kingdom)->entity->level, 1);
    }

    /** @test */
    function a_user_cannot_upgrade_a_library_with_insufficient_resources()
    {
        $this->expectException(CannotUpgradeException::class);
        $this->buildingService->constructLibrary($this->kingdom);
        $library = $this->buildingService->getBuilding($this->kingdom, Library::class);
        $this->buildingService->levelUp($this->kingdom, $library);
        $this->assertEquals($this->buildingService->getBuilding($this->kingdom, Library::class)->getLevel(), 2);
    }

    /** @test */
    function a_user_can_increase_the_level_of_a_library()
    {
        $this->kingdomService->giftResource($this->kingdom, Wood::class, 10000);
        $this->kingdomService->giftResource($this->kingdom, Stone::class, 10000);
        $this->kingdomService->giftResource($this->kingdom, Gold::class, 10000);
        $this->buildingService->constructLibrary($this->kingdom);
        $library = $this->buildingService->getLibrary($this->kingdom);
        $this->buildingService->levelUp($this->kingdom, $library);
        $this->assertEquals($this->buildingService->getLibrary($this->kingdom)->getLevel(), 2);
    }

    /** @test */
    function a_library_can_generate_resources()
    {
        $this->be($this->user);
        $this->kingdomService->giftResource($this->kingdom, Wood::class, 10000);
        $this->kingdomService->giftResource($this->kingdom, Stone::class, 10000);
        $this->kingdomService->giftResource($this->kingdom, Gold::class, 10000);
        $this->buildingService->constructLibrary($this->kingdom);

        $this->gameService->processTurn();
        $researchModel = ClassSaturator::instantiateClass(Research::class);

        $this->assertEquals(4, $this->kingdomService->getResourceAmount($this->kingdom->fresh(), $researchModel));
    }

    /** @test */
    public function a_user_can_construct_a_lumber_camp()
    {
        $this->kingdomService->giftResource($this->kingdom, Wood::class, 10000);
        $this->kingdomService->giftResource($this->kingdom, Stone::class, 10000);
        $this->kingdomService->giftResource($this->kingdom, Gold::class, 10000);
        $this->buildingService->constructBuilding($this->kingdom, LumberCamp::class);
        $this->assertInstanceOf(LumberCamp::class, $this->buildingService->getBuilding($this->kingdom, LumberCamp::class));
        $this->assertEquals($this->buildingService->getBuilding($this->kingdom, LumberCamp::class)->getKingdomId(), $this->kingdom->id);
        $this->assertEquals($this->buildingService->getBuilding($this->kingdom, LumberCamp::class)->getLevel(), 1);
    }

    /** @test */
    public function a_buildings_upgrade_cost_increases_exponentially()
    {
        $this->kingdomService->giftResource($this->kingdom, Wood::class, 1000000);
        $this->kingdomService->giftResource($this->kingdom, Stone::class, 1000000);
        $this->kingdomService->giftResource($this->kingdom, Gold::class, 1000000);
        $previousCosts = $this->buildingService->getUpgradeCost(ClassSaturator::instantiateClass(Library::class));

        $previousCost = 0;
        foreach ($previousCosts as $cost) {
            $previousCost += $cost;
        }
        $library = $this->buildingService->constructLibrary($this->kingdom);

        for ($i = 1; $i < 10; $i++) {

            $upgradeCosts = $this->buildingService->getUpgradeCost($library);
            $upgradeCost = 0;
            foreach ($upgradeCosts as $cost) {
                $upgradeCost += $cost;
            }
            $this->assertGreaterThan($previousCost * 2, $upgradeCost);
            $this->buildingService->levelUp($this->kingdom, $library);
        }
    }
}
