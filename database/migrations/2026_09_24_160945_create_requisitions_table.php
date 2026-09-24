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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ticket_id')->nullable();
            $table->integer('empresa_id')->index('empresa_id');
            $table->integer('agent_id')->index('agent_id');
            $table->integer('client_id')->nullable();
            $table->string('client_name');
            $table->enum('status', ['pending', 'delivered'])->default('pending');
            $table->integer('admin_id_delivered')->nullable();
            $table->longText('agent_signature')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('delivered_at')->nullable();
            $table->dateTime('signed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
