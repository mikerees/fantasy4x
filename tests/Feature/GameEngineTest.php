<?php


namespace Tests\Feature;


use App\Services\KingdomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GameEngineTest extends TestCase
{

    use RefreshDatabase;

    protected $kingdomService;


    protected function setUp()
    {
        parent::setUp();
        factory('App\Entities\Kingdom', 10)->create();

        $this->kingdomService = App::make(KingdomService::class);
    }

    /** @test */
    public function kingdoms_can_generate_ticks()
    {
        $kingdoms = $this->kingdomService->getKingdoms();
        $this->assertInstanceOf(Collection::class, $kingdoms);
        $kingdoms->each(function($kingdom) {
            $this->assertEquals(0, $kingdom->getTickCount());
        });

        Artisan::call("generate-ticks");
        $kingdoms->each(function($kingdom) {
            $this->assertEquals(5, $kingdom->getTickCount());
        });

    }

}