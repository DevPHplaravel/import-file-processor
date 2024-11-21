<?php

namespace DevPHPLaravel\ImportFileProcessor\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DevPHPLaravel\ImportFileProcessor\ProcessFinancialFile;

// Questo comando cerca tutti i file presenti nella cartella import-files.
// Ogni file trovato viene gestito in modo separato creando un job nella coda.
// I job contengono tutte le informazioni necessarie per processare i singoli file

class ImportFinancialData extends Command
{
    protected $signature = 'financial:import';
    protected $description = 'Importa i dati finanziari da file presenti nella cartella FTP';

    public function handle()
    {
        $this->info("Inizio elaborazione dei file...");

        $files = Storage::files('import-files');

        if (empty($files)) {
            $this->info("Nessun file trovato nella cartella import-files.");
            return Command::SUCCESS;
        }

        foreach ($files as $filePath) {
            try {
                // Aggiungi il job alla coda per ogni file
                ProcessFinancialFile::dispatch($filePath);
                $this->info("File aggiunto alla coda: {$filePath}");
            } catch (\Exception $e) {
                $this->error("Errore con il file {$filePath}: {$e->getMessage()}");
            }
        }

        $this->info("Elaborazione dei file in coda completata!");
        return Command::SUCCESS;
    }
}