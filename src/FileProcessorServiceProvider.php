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

        // Registro i comandi
        $this->commands([
        \DevPHPLaravel\ImportFileProcessor\Command\ImportFinancialData::class, // Comando nel pacchetto
    ]);

        // Pubblica il file di configurazione
    $this->publishes([
        __DIR__ . '/../src/config/file-processor.php' => config_path('file-processor.php'),
    ], 'config');


        // Carica le migrazioni
        $this->loadMigrationsFrom(__DIR__.'/../migrations');


        // Crea la directory import-files se non esiste
        $this->createImportFilesDirectory();
    }

    protected function createImportFilesDirectory()
    {
        // Usa il disco 'local' per creare la directory
        $path = 'import-files';

        // Verifica se la directory esiste, altrimenti la crea
        if (!Storage::disk('local')->exists($path)) {
            Storage::disk('local')->makeDirectory($path);
        }
    }
}