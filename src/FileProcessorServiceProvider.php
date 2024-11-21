<?php

namespace DevPHPLaravel\ImportFileProcessor;

use Illuminate\Support\ServiceProvider;

class FileProcessorServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registra eventuali binding o configurazioni
        $this->mergeConfigFrom(__DIR__ . '/../src/config/file-processor.php', 'file-processor');
    }

    public function boot()
    
    {

        // Pubblica il file di configurazione
    $this->publishes([
        __DIR__ . '/../src/config/file-processor.php' => config_path('file-processor.php'),
    ], 'config');


        // Carica le migrazioni
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }
}