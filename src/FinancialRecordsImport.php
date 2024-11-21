<?php

namespace DevPHPLaravel\ImportFileProcessor;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Log;


class FinancialRecordsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {

        
        foreach ($rows->toArray() as $row) {

            if (!isset($row['id_filiale']) || !isset($row['giorno']) || !isset($row['bilancio_giornaliero'])) {
                Log::channel('error_log')->error("Nomi dei label non corrispondenmti alla mappatura: " . json_encode($row));
            }
    
            // Conversione della data in formato Excel (giorno)
            $date = \Carbon\Carbon::createFromFormat('Y-m-d', \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['giorno']))->format('Y-m-d'));
        
            $financialRecord =  new FinancialRecord([
                'branch_id' => $row['id_filiale'], // Assicurati che il nome sia corretto
                'day' => $date,                    // Usa la data corretta
                'daily_balance' => $row['bilancio_giornaliero'],
            ]);

            $financialRecord->save();
        }
    }
}

