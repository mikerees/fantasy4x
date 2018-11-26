<?php

namespace App\Console\Commands;

use App\Entities\Kingdom;
use Illuminate\Console\Command;

class GenerateTicksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-ticks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate ticks for all kingdoms';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $kingdoms = Kingdom::all();

        $kingdoms->each(function($kingdom) {
            $kingdom->ticks += 5;
            $kingdom->save();
        });

        return true;
    }
}
