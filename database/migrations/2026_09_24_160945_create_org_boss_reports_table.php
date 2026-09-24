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
        Schema::create('org_boss_reports', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_obr_empresa');
            $table->integer('staff_id');
            $table->integer('organization_id')->index('idx_obr_org');
            $table->integer('target_user_id')->nullable();
            $table->string('subject');
            $table->mediumText('body_html')->nullable();
            $table->mediumText('body_text')->nullable();
            $table->dateTime('created_at')->useCurrent()->index('idx_obr_created');
            $table->dateTime('updated_at')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_boss_reports');
    }
};
