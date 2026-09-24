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
        DB::statement("DROP VIEW IF EXISTS `v_tickets_full`");
        DB::statement("CREATE VIEW `v_tickets_full` AS select `t`.`id` AS `id`,`t`.`ticket_number` AS `ticket_number`,`t`.`subject` AS `subject`,`u`.`firstname` AS `user_first`,`u`.`lastname` AS `user_last`,`u`.`email` AS `user_email`,ifnull(concat(`s`.`firstname`,' ',`s`.`lastname`),'Sin asignar') AS `staff_name`,`d`.`name` AS `dept_name`,`ts`.`name` AS `status_name`,`p`.`name` AS `priority_name`,`t`.`created` AS `created`,`t`.`updated` AS `updated`,`t`.`closed` AS `closed` from (((((`tickets` `t` join `users` `u` on((`t`.`user_id` = `u`.`id`))) left join `staff` `s` on((`t`.`staff_id` = `s`.`id`))) join `departments` `d` on((`t`.`dept_id` = `d`.`id`))) join `ticket_status` `ts` on((`t`.`status_id` = `ts`.`id`))) join `priorities` `p` on((`t`.`priority_id` = `p`.`id`)))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS `v_tickets_full`");
    }
};
