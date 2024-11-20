<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->date('day');
            $table->decimal('daily_balance', 15, 2);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('financial_records');
    }
}
