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
    const GAMIFIED_USER_TABLE = "gmdl_usuario";

    public static function create_gamified_user(core\event\user_created $event) {

        global $DB;

        $gamified_user = new stdClass();
        $gamified_user->mdl_id_usuario = $event->objectid; // or relateduserid, 
        $gamified_user->nivel_actual = self::DEFAULT_LEVEL;
        $gamified_user->experiencia_nivel  = self::DEFAULT_LEVELXP;
        $gamified_user->experiencia_actual = self::DEFAULT_XP;

        // Event Object Table is user
        $DB->insert_record( self::GAMIFIED_USER_TABLE, $gamified_user);
    }

}

?>
