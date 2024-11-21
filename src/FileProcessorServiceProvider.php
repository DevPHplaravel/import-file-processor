<?php

namespace DevPHPLaravel\ImportFileProcessor;

use Illuminate\Support\ServiceProvider;

class FileProcessorServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registra eventuali binding o configurazioni
        $this->mergeConfigFrom(__DIR__.'/../config/file-processor.php', 'file-processor');
    }

    public function boot()
    {
        // Carica le migrazioni
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }
}