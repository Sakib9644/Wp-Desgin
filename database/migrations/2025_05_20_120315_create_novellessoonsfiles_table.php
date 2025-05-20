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
        Schema::create('novellessoonsfiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('novelunitlessons')->onDelete('cascade');
            $table->enum('type', ['teacher edition', 'edition', 'powerpoint', 'worksheet']);
            $table->string('file');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novellessoonsfiles');
    }
};
