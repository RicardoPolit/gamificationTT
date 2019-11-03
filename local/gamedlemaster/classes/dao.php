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

class local_gamedlemaster_dao {

    const TBL_USER = "user";
    const TBL_GAMIFIED_USER = "gmdl_usuario";
    const TBL_GAMIFIED_USER_ATTR_USER = "mdl_id_usuario";
    const TBL_GAMIFIED_USER_ATTR_ID = "id";

    const TBL_REWARDED_SECTIONS = "gmdl_recompensas_seccion";
    const TBL_REWARDED_SECTIONS_ATTR_USER = "gmdl_id_usuario";

    /**
     * Esta función 
     *
     * @param moodle_user_id: Id de un usuario de moodle
     * @return id de un usario gamificado.
     */
    static function get_gamified_user($moodle_user_id) {

        global $DB;
        $conditions = array(
            self::TBL_GAMIFIED_USER_ATTR_USER => $moodle_user_id
        );

        return $DB->get_record( self::TBL_GAMIFIED_USER, $conditions);
    }

    /**
     * Elimina los registros de recompensas de secciones de un curso
     * de un usuario
     *
     * @param integer gamified_user: Id del usuario gamificado
     * @return bool: Status of operation.
     */ 
    static function delete_rewarded_sections($gamified_user) {
        global $DB;
        $conditions = array(
            self::TBL_REWARDED_SECTIONS_ATTR_USER => $gamified_user
        );

        return $DB->delete_records( self::TBL_REWARDED_SECTIONS, $conditions);
    }

    /**
     * Elimina los registros de experiencia deun usuario
     *
     * @param integer gamified_user: Id del usuario gamificado
     * @return bool: Status of operation.
     */ 
    static function delete_gamified_user($gamified_user) {
        global $DB;
        $conditions = array(
            self::TBL_GAMIFIED_USER_ATTR_ID => $gamified_user
        );

        return $DB->delete_records( self::TBL_GAMIFIED_USER, $conditions);
    }
}
?>
