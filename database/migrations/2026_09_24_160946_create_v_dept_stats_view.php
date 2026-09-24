<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("DROP VIEW IF EXISTS `v_dept_stats`");
        DB::statement("CREATE VIEW `v_dept_stats` AS select `d`.`id` AS `id`,`d`.`name` AS `name`,count(`t`.`id`) AS `total_tickets`,sum((case when (`t`.`status_id` = 1) then 1 else 0 end)) AS `open_tickets`,sum((case when (`t`.`status_id` = 2) then 1 else 0 end)) AS `in_progress`,sum((case when (`t`.`status_id` = 4) then 1 else 0 end)) AS `resolved`,sum((case when (`t`.`status_id` = 5) then 1 else 0 end)) AS `closed` from (`departments` `d` left join `tickets` `t` on((`d`.`id` = `t`.`dept_id`))) group by `d`.`id`,`d`.`name`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `v_dept_stats`");
    }
};
