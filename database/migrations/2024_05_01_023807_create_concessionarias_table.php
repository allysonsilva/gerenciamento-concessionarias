<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public const CNPJ_MAX_NUMBER_LENGTH = 14;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('concessionarias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('symbol');
            $table->string('cnpj', static::CNPJ_MAX_NUMBER_LENGTH);
            $table->timestamps();

            $table->unique(['symbol', 'cnpj']);

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concessionarias');
    }
};
