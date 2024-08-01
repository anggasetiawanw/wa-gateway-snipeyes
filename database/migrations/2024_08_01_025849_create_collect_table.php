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
        Schema::create('collect', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('ip')->nullable();
            $table->text('agent')->nullable();
            $table->string('network')->nullable();
            $table->string("latitude")->nullable();
            $table->string("longitude")->nullable();
            $table->string("number")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collect');
    }
};
