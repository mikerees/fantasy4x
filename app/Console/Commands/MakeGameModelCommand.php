<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeGameModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:game-model {type} {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a game model';

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
        $stub = file_get_contents(resource_path("stubs/model.stub"));

        $modelTemplate = str_replace(
            ['{{typeName}}', '{{modelName}}'],
            [Str::studly($this->argument("type")), Str::studly($this->argument("model"))],
            $stub
        );

        file_put_contents(app_path("/Models/".Str::studly($this->argument("type")."s/".Str::studly($this->argument("model")).".php")), $modelTemplate);
        $this->info("Model created successfully.");
    }
}
