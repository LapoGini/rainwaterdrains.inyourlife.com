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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('id_sd')->nullable();
            $table->string('id_da_app')->nullable();
            $table->timestamp('time_stamp_pulizia')->nullable();
            $table->double('caditoie_equiv')->nullable();
            $table->string('civic')->nullable();
            $table->decimal('longitude', 15, 5);
            $table->decimal('latitude', 15, 5);
            $table->decimal('altitude', 15, 5);
            $table->decimal('accuracy', 15, 5);
            $table->decimal('height', 15, 5);
            $table->decimal('width', 15, 5);
            $table->decimal('depth', 15, 5);
            $table->string('pic',500)->nullable();
            $table->string('note',500)->nullable();
            $table->foreignId('street_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('cancellabile')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
