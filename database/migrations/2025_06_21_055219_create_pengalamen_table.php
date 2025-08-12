<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengalaman', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('users_details_id');
            $table->text('pengalaman_kerja')->nullable();
            $table->text('pengalaman_organisasi')->nullable();
            $table->text('pengalaman_sertifikasi_pelatihan')->nullable();
            $table->string('pendidikan')->nullable();
            $table->timestamps();
            
$table->foreign('users_details_id')
      ->references('uuid')
      ->on('user_details') // ← sesuaikan dengan nama tabel
      ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengalaman');
    }
};
