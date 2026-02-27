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
        Schema::create('post_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('post_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('actor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('old_status')->nullable();
            $table->string('new_status');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['post_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_histories');
    }
};
