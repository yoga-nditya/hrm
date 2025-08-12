<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensiMagangTable extends Migration
{
    public function up()
    {
        Schema::create('absensi_magang', function (Blueprint $table) {
            $table->id();
            $table->uuid('application_uuid');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('application_uuid')->references('uuid')->on('status_lamarans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_magang');
    }
}
