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

class block_gmxp_dao {

    const PLUGIN = 'block_gmxp';
    const TBL_GAMIFIED_USER = 'gmdl_usuario';
    const TBL_COURSE_MODULES = 'course_modules';
    const TBL_COURSE_MODULES_ID = 'id';
    const TBL_COURSE_MODULES_COURSE = 'course';
    const TBL_COURSE_MODULES_SECTION = 'section';

    const TBL_MODULES_COMPLETION = 'course_modules_completion';
    const TBL_MODULES_COMPLETION_ID = 'id';

    static function get_level_xp($level) {
        $increment = get_config(self::PLUGIN, block_gmxp_core::INCREMENT);
        $value = get_config(self::PLUGIN, block_gmxp_core::VALUE);
        $initial = get_config(self::PLUGIN, block_gmxp_core::LEVELXP);

        if ($increment === block_gmxp_core::LINEAL) {
            $xp = $initial + ( $value * ($level - 1) );

        } else { // PORCENTUAL
            $xp = $initial * pow( $value, $level - 1 );
        }

        return round($xp);
    }

    static function bring_experience($userid, $xp) {
        global $DB;

        $user = $DB->get_record('gmdl_usuario', array('id' => $userid));
        $levelxp = self::get_level_xp($user->nivel_actual);

        $xp_points = $xp + $user->experiencia_nivel;
        
        if ( $xp_points >= $levelxp ) {
            $user->nivel_actual++;
            $user->experiencia_actual += $levelxp - $user->experiencia_nivel;
            $user->experiencia_nivel = 0;
            $DB->update_record('gmdl_usuario', $user);
            local_gamedlemaster_log::info($user, 'UPDATE LVL');
            self::bring_experience($userid, $xp_points - $levelxp);
        } else {
            $user->experiencia_nivel += $xp;
            $user->experiencia_actual += $xp;
            local_gamedlemaster_log::info($user, 'UPDATE XP');
            $DB->update_record('gmdl_usuario', $user);
        }
    }

    static function level_up($user) {

        global $DB;
        $levelxp = self::get_level_xp($user->nivel_actual);

        if ($user->experiencia_nivel >= $levelxp) {
            $user->nivel_actual++;
            $user->experiencia_nivel -= $levelxp;
            return self::level_up($user);

        } else {
            local_gamedlemaster_log::info(
                "User {$user->mdl_id_usuario} reached level {$user->nivel_actual}",
                'UPDATE');

            return $DB->update_record(self::TBL_GAMIFIED_USER, $user);
        }
    }

    public function is_section_completed($section) {
    }

    /**
     * @return module object of table TBL_MODULES_COMPLETION
     */
    public function get_course_module_completion($completion_id) {
        global $DB; // Table, selection, projectionº
        return $DB->get_record( self::TBL_MODULES_COMPLETION,
            array( self::TBL_MODULES_COMPLETION_ID => $completion_id ));
    }

    /**
     * @return module object of table TBL_COURSE_MODULES
     */
    public function get_course_module($module_id) {
        global $DB; // Table, selection, projection
        return $DB->get_record( self::TBL_COURSE_MODULES,
            array( self::TBL_COURSE_MODULES_ID => $module_id ));
    }

    /**:
     * @return 
     */ 
    public function get_modules_in_section($course, $section) {
        global $DB; // Table, selection, projection
        return $DB->get_records( self::TBL_COURSE_MODULES,
            array( self::TBL_COURSE_MODULES_ID => $module_id ));
    }
}
?>
