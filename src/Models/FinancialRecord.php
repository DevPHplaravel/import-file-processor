<?php

namespace DevPHPLaravel\ImportFileProcessor\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialRecord extends Model
{


    protected $fillable = [
        'branch_id',
        'day',
        'daily_balance',
    ];

}
