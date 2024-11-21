<?php

namespace DevPHPLaravel\ImportFileProcessor\Services;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use AlessandroMineo\ImportFileProcessor\Models\FinacialRecord;
use Illuminate\Support\Facades\Storage;

class JsonProcessor implements FileProcessorInterface
{
    public function process(string $filePath): void
    {
        Log::channel('import')->info("Inizio importazione del file: {$filePath}");
        
        $hasError = false;  // Variabile per tracciare se ci sono errori nel file

        try {
            // Leggi e decodifica il file JSON
            $content = file_get_contents(storage_path("app/{$filePath}"));
            $data = json_decode($content, true);

            // Verifica se la decodifica è riuscita
            if ($data === null) {
                throw new \Exception("Errore nel parsing del file JSON.");
            }

            $importedCount = 0;
            foreach ($data as $row) {
                // Verifica che tutti i campi necessari siano presenti
                if (!isset($row['id_filiale'], $row['giorno'], $row['bilancio_giornaliero'])) {
                    Log::channel('import')->error("Dati mancanti per il record: " . json_encode($row));
                    $hasError = true;  // Segna l'errore
                    continue;  // Salta questo record
                }

                // Verifica che il formato della data sia corretto
                try {
                    $day = Carbon::createFromFormat('d/m/Y', $row['giorno']);
                } catch (\Exception $e) {
                    Log::channel('import')->error("Data non valida per il record: " . json_encode($row));
                    $hasError = true;  // Segna l'errore
                    continue;  // Salta questo record
                }

                try {
                    // Crea il record nel database
                    FinancialRecord::create([
                        'branch_id' => $row['id_filiale'],
                        'day' => $day,
                        'daily_balance' => str_replace(',', '.', $row['bilancio_giornaliero']),
                    ]);

                    $importedCount++;
                } catch (\Exception $e) {
                    // Log l'errore per il singolo record
                    Log::channel('import')->error("Errore record: " . json_encode($row) . " - " . $e->getMessage());
                    $hasError = true;  // Segna l'errore
                }
            }

            // Log del risultato dell'importazione
            Log::channel('import')->info("Importazione completata: {$importedCount} records importati.");

            // Sposta il file nella cartella appropriata
            if ($hasError) {
                // Se ci sono errori, sposta il file nella cartella error-files
                Log::channel('import')->info("Spostamento del file {$filePath} nella cartella error-files.");
                Storage::move($filePath, 'error-files/' . basename($filePath));
            } else {
                // Se non ci sono errori, sposta il file nella cartella processed-files
                Log::channel('import')->info("Spostamento del file {$filePath} nella cartella processed-files.");
                Storage::move($filePath, 'processed-files/' . basename($filePath));
            }
            
        } catch (\Exception $e) {
            // Log dell'errore generale
            Log::channel('import')->error("Errore file {$filePath}: " . $e->getMessage());

            // Sposta il file nella cartella error-files in caso di errore generale
            Storage::move($filePath, 'error-files/' . basename($filePath));
        }
    }
}
