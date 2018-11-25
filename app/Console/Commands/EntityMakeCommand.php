<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;

class EntityMakeCommand extends ModelMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:entity {name} {--plural=?} {--feature : create a feature test} {--unit : create a unit test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create entity and all associated classes. PLEASE SEPARATE WORDS WITH SPACES';

    protected $pluralValue;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->pluralValue = Str::plural($this->input->getArgument("name"));
        if ($this->input->getOption("plural")) {
            $this->pluralValue = $this->input->getOption("plural");
        }

        $this->info("Creating migration");
        $this->createMigration();
        $this->info("Creating factory");
        $this->createFactory();
        $this->info("Creating controller");
        $this->createController();
        $this->info("Creating service");
        $this->createService();
        $this->info("Creating tests...");
        $this->createTests();
        $this->info("All done! Have some fun.");

    }

    protected function createMigration()
    {
        $this->call('make:model', [
            'name' => "App\\Entities\\".Str::studly($this->argument("name"))
        ]);
        $this->call("make:migration", [
            "name" => "create_".Str::snake(strtolower($this->pluralValue))."_table",
            '--create' => Str::snake(strtolower($this->pluralValue))
        ]);
    }

    protected function createTests()
    {
        if ($this->option("feature")) {
            $this->call("make:test", [
                'name' => Str::studly($this->argument("name"))."Test"
            ]);
        }
        if ($this->option("unit")) {
            $this->call("make:test", [
                'name' => Str::studly($this->argument("name"))."Test",
                '--unit' => true
            ]);
        }
    }

    protected function createFactory()
    {
        $this->call("make:factory", [
            'name' => Str::studly($this->argument("name"))."Factory",
            "--model" => "App\\Entities\\".Str::studly($this->argument("name"))
        ]);
    }

    protected function createController()
    {
        $this->call('make:controller', [
            'name' => Str::studly($this->argument("name"))."Controller",
            '--model' => "App\\Entities\\".Str::studly($this->argument("name"))
        ]);
    }

    protected function createService()
    {
        $stub = file_get_contents(resource_path("stubs/service.stub"));

        $serviceTemplate = str_replace(
            ['{{modelName}}'],
            [Str::studly($this->argument("name"))],
            $stub
        );

        file_put_contents(app_path("/Services/".Str::studly($this->argument("name")."Service.php")), $serviceTemplate);
        $this->info("Service created successfully.");
    }
}
