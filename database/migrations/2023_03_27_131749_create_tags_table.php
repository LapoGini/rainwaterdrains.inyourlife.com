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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('description')->nullable();
            // rimuovere la colonna type
            // $table->dropColumn('type');
            $table->foreignId('type_id')->constrained('tag_types');
            $table->string('domain',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};

// modificata la colonna type (stringa) per sostituirla con l'id della tipologia che arriva dalla tabella tag_types
// ->constrained('tag_types');       Crea una chiave esterna a 'tag_types'