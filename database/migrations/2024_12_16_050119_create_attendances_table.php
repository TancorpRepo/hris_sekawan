<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->text('PersonnelNo');
            $table->dateTime('jam');
            $table->text('status');
            $table->text('idTR')->nullable();
            $table->text('Verify')->nullable();
            $table->text('SN')->nullable();
            $table->text('Longitude')->nullable();
            $table->text('Latitude')->nullable();
            $table->text('IP')->nullable();
            $table->boolean('Sync')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
