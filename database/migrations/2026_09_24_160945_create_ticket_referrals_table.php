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
        Schema::create('ticket_referrals', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ticket_id')->index('idx_ticket');
            $table->integer('staff_id')->nullable()->index('idx_staff');
            $table->integer('dept_id')->nullable()->index('idx_dept');
            $table->dateTime('created')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_referrals');
    }
};
