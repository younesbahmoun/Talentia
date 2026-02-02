<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {   
        Schema::create('profiles', function (Blueprint $table) {
            // // Utiliser 'utilisateur_id' comme dans votre relation
            // $table->foreignId('utilisateur_id')  // Changez de 'user_id' à 'utilisateur_id'
            //     ->constrained('users', 'id')   // Référence explicite à users.id
            //     ->onDelete('cascade')
            //     ->unique(); // Pour relation one-to-one
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('titre')->nullable();
            $table->text('formation')->nullable();
            $table->text('experiences')->nullable();
            $table->text('competences')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
