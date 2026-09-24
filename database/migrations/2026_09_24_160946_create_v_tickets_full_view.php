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
        DB::statement("CREATE OR REPLACE VIEW `v_tickets_full` AS select `t`.`id` AS `id`,`t`.`ticket_number` AS `ticket_number`,`t`.`subject` AS `subject`,`u`.`firstname` AS `user_first`,`u`.`lastname` AS `user_last`,`u`.`email` AS `user_email`,ifnull(concat(`s`.`firstname`,' ',`s`.`lastname`),'Sin asignar') AS `staff_name`,`d`.`name` AS `dept_name`,`ts`.`name` AS `status_name`,`p`.`name` AS `priority_name`,`t`.`created` AS `created`,`t`.`updated` AS `updated`,`t`.`closed` AS `closed` from (((((`tickets_laravel_estructura`.`tickets` `t` join `tickets_laravel_estructura`.`users` `u` on((`t`.`user_id` = `u`.`id`))) left join `tickets_laravel_estructura`.`staff` `s` on((`t`.`staff_id` = `s`.`id`))) join `tickets_laravel_estructura`.`departments` `d` on((`t`.`dept_id` = `d`.`id`))) join `tickets_laravel_estructura`.`ticket_status` `ts` on((`t`.`status_id` = `ts`.`id`))) join `tickets_laravel_estructura`.`priorities` `p` on((`t`.`priority_id` = `p`.`id`)))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `v_tickets_full`");
    }
};
