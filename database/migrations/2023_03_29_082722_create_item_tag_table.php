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
        Schema::create('item_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();

            // Rimuovere la foreign key tag_id
            //$table->dropForeign(['tag_id']);

            $table->foreignId('recapito_tag_id')->nullable()->constrained('tags');
            $table->foreignId('stato_tag_id')->nullable()->constrained('tags');
            $table->foreignId('tipologia_tag_id')->nullable()->constrained('tags');

            // Rimuovere la colonna tag_id
            //$table->dropColumn('tag_id');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_tag');
    }
};

// rimossa la colonna tag_id e la foreing key del tag_id
// aggiunte le tre colonne separate per i tipi di tag creando una chiave esterna a tags