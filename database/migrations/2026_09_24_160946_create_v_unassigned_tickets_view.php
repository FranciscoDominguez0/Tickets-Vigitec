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
        DB::statement("CREATE VIEW `v_unassigned_tickets` AS select `t`.`id` AS `id`,`t`.`ticket_number` AS `ticket_number`,`t`.`subject` AS `subject`,concat(`u`.`firstname`,' ',`u`.`lastname`) AS `user_name`,`d`.`name` AS `dept_name`,`ts`.`name` AS `status_name`,`p`.`name` AS `priority_name`,`t`.`created` AS `created` from ((((`tickets_laravel_estructura`.`tickets` `t` join `tickets_laravel_estructura`.`users` `u` on((`t`.`user_id` = `u`.`id`))) join `tickets_laravel_estructura`.`departments` `d` on((`t`.`dept_id` = `d`.`id`))) join `tickets_laravel_estructura`.`ticket_status` `ts` on((`t`.`status_id` = `ts`.`id`))) join `tickets_laravel_estructura`.`priorities` `p` on((`t`.`priority_id` = `p`.`id`))) where ((`t`.`staff_id` is null) and (`t`.`status_id` <> 5))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `v_unassigned_tickets`");
    }
};
