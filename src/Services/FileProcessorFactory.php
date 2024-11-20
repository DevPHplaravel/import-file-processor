<?php

namespace AlessandroMineo\ImportFileProcessor\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileProcessorFactory
{
    public static function create(string $filePath): FileProcessorInterface
    {
        $extension = Str::lower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($extension) {
            'csv', 'xlsx' => new CsvExcelProcessor(),
            'json' => new JsonProcessor(),
            'xml' => new XmlProcessor(),
            default => self::handleUnsupportedFormat($filePath, $extension),
        };
    }

    //funzione che gestisce i file non supportati, che si occupa di loggare l'errore e spostare il file nella directory dei file con errori
    private static function handleUnsupportedFormat(string $filePath, string $extension)
    {
        // Log per formato non supportato
        Log::channel('error_log')->error("Formato non supportato per il file: {$filePath} con estensione {$extension}");

        // Sposta il file nella cartella degli errori
        $errorFilePath = 'error-files/' . basename($filePath);
        Storage::move($filePath, $errorFilePath);

        throw new \Exception("Formato non supportato: {$extension}");
    }
}
