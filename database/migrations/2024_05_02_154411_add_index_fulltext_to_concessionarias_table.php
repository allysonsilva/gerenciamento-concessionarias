<?php

use App\Models\Concessionaria;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('concessionarias', function (Blueprint $table) {
            $table->fullText(Concessionaria::columnsToFullTextSearch());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concessionarias', function (Blueprint $table) {
            $table->dropFullText(Concessionaria::columnsToFullTextSearch());
        });
    }
};
