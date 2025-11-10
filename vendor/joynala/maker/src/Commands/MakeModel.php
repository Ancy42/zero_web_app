<?php

namespace Abedin\Maker\Commands;

use Abedin\Maker\Lib\Generator\Model;
use Abedin\Maker\Lib\Generator\Repository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModel extends Command
{

    // The name and signature of the command.
    protected $signature = 'make:model {models?*} {--m|migration} {--c|controller} {--r|resource}';

    // The console command description.
    protected $description = 'modify Eloquent model command, optionally with migration, controller, and resource options.';

    // Execute the console command.
    public function handle()
    {
        // Get the model names
        $models = match(empty($this->argument('models'))){
            true => explode(' ', $this->ask('Enter Model/Models Name')),
            default => $this->argument('models')
        };
        // confirmation message
        $this->info("==> I just need a confirmation on whether I will create the repository with the model.\nif you agree I just create the repository with model.");

        // get an answer on Whether to create the repository
        $makeWithRepo = $this->confirm('Do you want to create repository?');
        $this->info('Thanks for your confirmation.');

        // get all exists moldes for ignore creating model
        $existsModels = Model::existsModels();

        // get all exists repositories for ignore creating repository
        $existsRepositories = Repository::existsRepositories();

        if(!is_array($models)){
            $models = [$models];
        }

        foreach($models as $modelName){
            $modelName = ucfirst($modelName);

            // check is exists repositories and ignore
            if($makeWithRepo && !in_array($modelName . 'Repository', $existsRepositories)){
                Repository::generate($modelName);
            }

            // Check if -c option is passed
            if ($this->option('controller')) {
                if ($this->option('resource')) {
                    $this->call('make:controller', ['name' => "{$modelName}Controller", '--resource' => true]);
                } else {
                    $this->call('make:controller', ['name' => "{$modelName}Controller"]);
                }
            }

            // check is exists models and ignore
            if(!in_array($modelName, $existsModels)){
                Model::generate($modelName);
            }

            if ($this->option('migration')) {
                $this->createMigration($modelName);
                sleep(1);
            }
        }

        $this->info('Model/Models is created successfully.');
        if($this->option('migration')){
            $this->info('Migration/Migrations is created successfully.');
        }
        if($this->option('controller')){
            $this->info('Controller/Controllers is created successfully.');
        }
    }


    protected function createMigration($modelName): void
    {
        // Replace uppercase letters with underscores and lowercase versions
        $modelName = preg_replace('/([a-z])([A-Z])/', '$1_$2', $modelName);

        // Convert the result to lowercase
        $modelName = strtolower($modelName);

        // make plurar model name
        $tableName = Str::plural(strtolower($modelName));
        $migrationName = "create_{$tableName}_table";

        $this->call('make:migration', [
            'name' => $migrationName,
            '--create' => $tableName,
        ]);
    }
}
