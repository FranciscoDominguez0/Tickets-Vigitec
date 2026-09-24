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
        Schema::create('quotes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('empresa_id');
            $table->integer('ticket_id')->nullable()->index('ticket_id');
            $table->integer('org_id')->index('org_id');
            $table->integer('staff_id')->index('staff_id');
            $table->string('title');
            $table->string('sucursal')->nullable()->default('');
            $table->text('description')->nullable();
            $table->decimal('amount', 10)->nullable()->default(0);
            $table->enum('status', ['draft', 'pending', 'requested', 'answered', 'accepted', 'rejected', 'waiting_oc'])->default('draft');
            $table->string('file_path')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
