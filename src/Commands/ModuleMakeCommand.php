<?php

namespace Nwidart\Modules\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Generators\ModuleGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ModuleMakeCommand extends Command
{
    use \Modules\Core\Traits\ModuleGenerator;
    
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $names = $this->argument('name');

        foreach ($names as $name) {

            $this->createModuleEntry($name);

            with(new ModuleGenerator($name))
                ->setFilesystem($this->laravel['files'])
                ->setModule($this->laravel['modules'])
                ->setConfig($this->laravel['config'])
                ->setConsole($this)
                ->setForce($this->option('force'))
                ->setPlain($this->option('plain'))
                ->generate();
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::IS_ARRAY, 'The names of modules will be created.'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain module (without some resources).'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when the module already exists.'],
            ['can_be_disabled', null, InputOption::VALUE_NONE, 'Condition for letting a module to be disabled.'],
            ['can_not_be_disabled', null, InputOption::VALUE_NONE, 'Condition for not letting a module to be disabled.'],
            ['enabled', null, InputOption::VALUE_NONE, 'Condition for a module to enable.'],
            ['disabled', null, InputOption::VALUE_NONE, 'Condition for a module to disable.'],
        ];
    }
}