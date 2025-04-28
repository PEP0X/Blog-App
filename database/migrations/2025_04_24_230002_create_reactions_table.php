<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('reactable'); // This will create reactable_id and reactable_type columns
            $table->enum('type', ['like', 'dislike', 'love', 'wow', 'clap', 'encourage']);
            $table->timestamps();
            
            // Ensure a user can only have one reaction per item
            $table->unique(['user_id', 'reactable_id', 'reactable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
}; 