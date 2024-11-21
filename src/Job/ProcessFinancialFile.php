<?php

namespace DevPHPLaravel\ImportFileProcessor\Job;

use DevPHPLaravel\ImportFileProcessor\Services\FileProcessorFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use DevPHPLaravel\ImportFileProcessor\FinancialRecordsImport;

// Gestione tramite la coda dei job
//  Una volta che i job sono stati messi nella coda, un worker esegue questi job

class ProcessFinancialFile implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        try {
            // Crea il processor giusto per il file
            $processor = FileProcessorFactory::create($this->filePath);
            $processor->process($this->filePath);  // Esegue il processo del file

            // Sposta il file elaborato nella cartella processed-files
            Storage::move($this->filePath, 'processed-files/' . basename($this->filePath));
        } catch (\Exception $e) {
            // Gestisci errori e sposta il file nella cartella error-files
            Storage::move($this->filePath, 'error-files/' . basename($this->filePath));
        }
    }
}

