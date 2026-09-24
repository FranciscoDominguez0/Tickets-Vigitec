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
        Schema::create('ticket_report_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresa_id')->default(1)->index();
            $table->unsignedInteger('report_id')->index();
            $table->text('description');
            $table->decimal('price', 10)->default(0);
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_report_items');
    }
};
