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

    const TBL_COURSE_MODULES = 'course_modules';
    const TBL_COURSE_MODULES_ID = 'id';
    const TBL_COURSE_MODULES_COURSE = 'course';
    const TBL_COURSE_MODULES_SECTION = 'section';

    const TBL_MODULES_COMPLETION = 'course_modules_completion';
    const TBL_MODULES_COMPLETION_ID = 'id';

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
