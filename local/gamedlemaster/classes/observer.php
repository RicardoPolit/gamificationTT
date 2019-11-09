<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *
 *   PROJECT:     Gamedle
 *   PROFESSOR:   Sandra Bautista, Andres Catalan.
 *
 * DESARROLLADORES: Daniel Ortega (GitLab/Github @DanielOrtegaZ)
*/

defined('MOODLE_INTERNAL') || die();

class local_gamedlemaster_observer {

    const DEFAULT_LEVEL = 1;
    const DEFAULT_LEVELXP = 0;
    const DEFAULT_XP = 0;

    public static function create_gamified_user(core\event\user_created $event) {

        global $DB;

        $id = local_gamedlemaster_dao::create_gamified_user($event->objectid);

        if($id) {
            local_gamedlemaster_log::success(
                "Gamedle user {$id} from moodle user {$event->objectid}",'CREATE');
        } else {
            local_gamedlemaster_log::error(
                "Gamedle user {$id} from moodle user {$event->objectid}",'CREATE');
        }
    }

    public static function delete_gamified_user(core\event\user_deleted $event) {

        global $DB;
        $gamified_user = local_gamedlemaster_dao::get_gamified_user($event->objectid);

        try {
            $transaction = $DB->start_delegated_transaction();

            $a = local_gamedlemaster_dao::delete_rewarded_sections($gamified_user->id);
            $b = local_gamedlemaster_dao::delete_gamified_user($gamified_user->id);

            if ($a == 1 && $b == 1 ) {
                $transaction->allow_commit();
                local_gamedlemaster_log::warning( "Gamedle user {$gamified_user->id} ".
                    "from moodle user {$event->objectid}",'DELETE');

            } else {
                $transaction->rollback();
                throw new coding_exception(
                    "Delete gamified user: {$b}, ".
                    "Delete rewarded sections {$a}");
            }

        } catch(Exception $ex) {
            $transaction->rollback($ex);
            local_gamedlemaster_log::error( "Gamedle user {$gamified_user->id} ".
                "from moodle user {$event->objectid}",'DELETE');
        }
    }

}

?>
