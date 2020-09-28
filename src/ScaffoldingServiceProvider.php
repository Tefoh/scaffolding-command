<?php

namespace Scaffolding;

use Illuminate\Support\ServiceProvider;

class ScaffoldingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureCommands();
        $this->configurePublishing();
    }

    public function configureCommands()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }
        
        $this->commands([
            Console\ScaffoldCommand::class,
        ]);
    }

    public function configurePublishing()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }
        
        $this->publishes([
            __DIR__.'/../stubs' => base_path('stubs'),
        ], 'scaffolding-stubs');

    }
}