<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTDSDataTable extends Migration
{
    public function up()
    {
        Schema::create('tdsdata', function (Blueprint $table) {
            $table->id();
            $table->float('tds_value');
            $table->integer('total_galon');
            $table->string('quality');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tdsdata');
    }
}
