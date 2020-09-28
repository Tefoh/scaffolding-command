<?php

namespace Scaffolding\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ScaffoldCommand extends Command
{
    protected $signature = 'scaffolding {entity : the name of entity}';

    protected $description = 'scaffold the entity for all necessary files';

    public function handle()
    {
        $this->callSilent('make:model', ['name' => ucfirst($this->argument('entity')), '-a' => true, '--force' => true]);
        $this->callSilent('make:request', ['name' => ucfirst($this->argument('entity')).'Request']);
        $this->callSilent('make:test', ['name' => ucfirst($this->argument('entity')).'Test']);
        $this->callSilent('make:test', ['name' => ucfirst($this->argument('entity')).'Test', '--unit' => true]);

        if (! (new Filesystem)->isDirectory(resource_path('views/'.strtolower($this->argument('entity'))))) {
            (new Filesystem)->makeDirectory(resource_path('views/'.strtolower($this->argument('entity'))));
        }
        copy(base_path('stubs/index.stub'), resource_path('views/'.strtolower($this->argument('entity')).'/index.blade.php'));
        copy(base_path('stubs/create.stub'), resource_path('views/'.strtolower($this->argument('entity')).'/create.blade.php'));
        copy(base_path('stubs/show.stub'), resource_path('views/'.strtolower($this->argument('entity')).'/show.blade.php'));
        copy(base_path('stubs/edit.stub'), resource_path('views/'.strtolower($this->argument('entity')).'/edit.blade.php'));

        file_put_contents(
            base_path('routes/web.php'),
            "\n\nuse App\Http\Controllers\\".ucfirst($this->argument('entity')) ."Controller;\n\nRoute::resource('/".strtolower($this->argument('entity'))."', ". ucfirst($this->argument('entity')) ."Controller::class);",
            FILE_APPEND
        );
    }
}