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
        Schema::create('websitesetups', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('site_icon')->nullable();
            $table->string('website_name')->nullable();
            $table->string('admin_footer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websitesetups');
    }
};
