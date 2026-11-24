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
        Schema::create('ticket_collaborators', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_id')->index();
            $table->integer('empresa_id')->index();
            $table->integer('user_id')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('role')->default('colaborador');
            $table->timestamps();

            $table->unique(['ticket_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_collaborators');
    }
};
