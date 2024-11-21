<?php

namespace DevPHPLaravel\ImportFileProcessor;

use Illuminate\Support\ServiceProvider;

class FileProcessorServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registro eventuali binding o configurazioni
        $this->mergeConfigFrom(__DIR__ . '/../src/config/file-processor.php', 'file-processor');
    }

    public function boot()
    {

        // Registro i comandi
        $this->commands([
        \DevPHPLaravel\ImportFileProcessor\Command\ImportFinancialData::class, // Comando nel pacchetto
    ]);

        // Pubblico il file di configurazione
    $this->publishes([
        __DIR__ . '/../src/config/file-processor.php' => config_path('file-processor.php'),
    ], 'config');


        // Carico le migrazioni
        $this->loadMigrationsFrom(__DIR__.'/../migrations');


        // Creo la directory import-files se non esiste
        $this->createImportFilesDirectory();
    }

    protected function createImportFilesDirectory()
    {
        // Uso il disco 'local' per creare la directory
        $path = 'import-files';

        // Verifica se la directory esiste, altrimenti la crea
        if (!Storage::disk('local')->exists($path)) {
            Storage::disk('local')->makeDirectory($path);
        }
    }
}