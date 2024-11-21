<?php

namespace DevPHPLaravel\ImportFileProcessor\Services;

use Maatwebsite\Excel\Facades\Excel;
use DevPHPLaravel\ImportFileProcessor\FinancialRecordsImport; 
use Illuminate\Support\Facades\Log;

class CsvExcelProcessor implements FileProcessorInterface
{
    public function process(string $filePath): void
    {
       

        try {
            Excel::import(new FinancialRecordsImport, storage_path("app/{$filePath}"));

             // Log del completamento dell'importazione
             Log::channel('import')->info("Importazione completata per il file: {$filePath}");

        } catch (\Throwable $e) {
            throw new \Exception("Errore durante l'importazione del file: " . $e->getMessage());
             // Log dell'errore
             Log::channel('error_log')->error("Errore durante l'importazione del file: {$filePath} - " . $e->getMessage());
        }
        
    }
}

