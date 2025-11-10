<?php

namespace Abedin\Maker\Commands;

use Abedin\Maker\Lib\Generator\Model;
use Abedin\Maker\Lib\Generator\Repository;
use Illuminate\Console\Command;

class MakeRepository extends Command
{

    // The name and signature of the command.
    protected $signature = 'make:repository {--a|all}';

    // The console command description.
    protected $description = 'Create a new repository.';

    // Execute the console command.
    public function handle(): void
    {
        if($this->option('all')){
            $models = Model::existsModels();
            $this->make($models);
        }else{
            // Get the repository names
            $this->info('==> Please enter the name of the model or models you want to create a repository for.');
            $models = $this->ask('Enter Model/Models Name');

            if(!is_array($models)){
                $models = [$models];
            }
            $this->make($models);
        }

        $this->info('Repository/Repositorys is created successfully.');
    }

    private function make(array $models): void
    {
        $existsRepositories = Repository::existsRepositories();
        foreach ($models as $modelName) {
            $modelName = ucfirst($modelName);
            if(!in_array($modelName . 'Repository', $existsRepositories)){
                Repository::generate($modelName);
            }
        }
    }
}
