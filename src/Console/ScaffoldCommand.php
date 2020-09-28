<?php

namespace Scaffolding\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ScaffoldCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffolding {entity : the name of entity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scaffold the entity for all necessary files';

    /**
     * views need to be generated for crud operation.
     *
     * @var string
     */
    protected $resourceViews = [
        'index',
        'create',
        'show',
        'edit',
    ];

    /**
     * Execute the console command that create all files.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('make:model', [
            'name' => ucfirst($this->argument('entity')), '-a' => true, '--force' => true
        ]);
        $this->callSilent('make:request', [
            'name' => ucfirst($this->argument('entity')).'Request'
        ]);
        $this->callSilent('make:test', [
            'name' => ucfirst($this->argument('entity')).'Test'
        ]);
        $this->callSilent('make:test', [
            'name' => ucfirst($this->argument('entity')).'Test', '--unit' => true
        ]);

        $viewResourceDir = 'views/' . strtolower($this->argument('entity'));
        if (! (new Filesystem)->isDirectory(resource_path($viewResourceDir))) {
            (new Filesystem)->makeDirectory(resource_path($viewResourceDir));
        }

        foreach ($this->resourceViews as $view) {
            if (! (new Filesystem)->isFile(resource_path($viewResourceDir.'/'.$view.'.blade.php')))
                continue;

            copy(
                base_path('stubs/'.$view.'.stub'),
                resource_path($viewResourceDir.'/'.$view.'.blade.php')
            );
        }

        file_put_contents(
            base_path('routes/web.php'),
            "\n\nuse App\Http\Controllers\\".ucfirst($this->argument('entity')) ."Controller;\n\nRoute::resource('/".strtolower($this->argument('entity'))."', ". ucfirst($this->argument('entity')) ."Controller::class);",
            FILE_APPEND
        );
    }
}
