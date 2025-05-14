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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('flag');
            $table->string('code', 2);
            $table->string('dial_code');
            $table->longText('region_code', 2)->nullable();
            $table->json('timezones')->nullable();
            $table->json('languages')->nullable();
            $table->json('language_codes')->nullable();
            $table->string('region')->nullable();
            $table->integer('min_length');
            $table->integer('max_length');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
