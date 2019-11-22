<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *   SUBJECT:     _
 *   PROFESSOR:   _
 *
 * DESARROLLADORES: Daniel Ortega
 *
 * PRACTICE _:  TITULO DE LA PRACTICA
 *               - DESCRIPCION
 * 
*/

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/course/format/lib.php');

class format_gamedle_observer {
    
    private static function checkSession(){
        if(!isset($_SESSION['Gamedle']['format']))
            $_SESSION['Gamedle']['format'] = "";
    }
    
    /*public static function course_created(core\event\course_created $event){
    
        // Saving course id with sections
        $courseid = $event->get_data()['courseid'];
        format_gamedle_observer::$sectionsXP[$courseid] = -1;
    }*/
    
    public static function course_section_created(core\event\course_section_created $event){
        
        $courseid = $event->get_data()['courseid'];
        $section  = $event->get_data()['other']['sectionnum'];
        $format   = course_get_format($courseid);
        
        if ($format->get_format() === "gamedle") {
            //if($format->experienceEnabled()) {
                $id = $format->createSectionXP($section);
            //}
        }
    }
    
    public static function enrol_instance_created(core\event\enrol_instance_created $event){
        
        $courseid = $event->get_data()['courseid'];
        $format   = course_get_format($courseid);
        
        if( $format->get_format() === "gamedle" ) {
            //if( $format->experienceEnabled() ) {

                if( !$format->isExperienceSet() ) {
                    $format->setDefaultSectionXP();
                    local_gamedlemaster_log::success(
                        "Default experience set for course {$courseid}", "FORMAT");
                }
            //}
        }
    }

    public static function course_updated(core\event\course_updated $event) {

        global $DB;
        $courseid = $event->get_data()['courseid'];
        $format   = course_get_format($courseid);

        if ($format->get_format() === "gamedle") {

            // El nuevo formato es un formato es gamedle

            $conditions = array( 'course' => $courseid );
            $sections = $DB->get_records('course_sections', $conditions);

            $users = $DB->get_records('course_modules_completion',
                                      null, '', 'DISTINCT userid' );

            $default = false;
            foreach ($sections as $section) {

                $exists = $DB->get_record('gmdl_seccion_curso', array(
                    'mdl_id_seccion_curso' => $section->id,
                ));

                if (!$exists) {
                    $format->createSectionXP($section->section,$section->id);
                    $default = true;
                }
            }

            if ($default) {
                $format->setDefaultSectionXP();
            }

            // Por cada usuario
            foreach($users as $user) {
                foreach($sections as $section) {

                    // y por cada seccion que no sea la seccion 0 
                    if ($section->section != 0) {
                        $conditions = array( 'section' => $section->id );
                        $modules = $DB->get_records('course_modules', $conditions);
    
                        // Revisa si ya completo todas las actividades del curso
                        $completed = true;
                        foreach($modules as $module) {

                            $conditions = array(
                                'coursemoduleid' => $module->id,
                                'userid' => $user->userid
                            );

                            $completion = $DB->get_record('course_modules_completion',
                                $conditions
                            );

                            $completed = $completed && (boolean)$completion;
                            $completed = $completed && $completion->completionstate;
                        }

                        if($completed) {
                            $format->bring_xp($user->userid,$section->id);
                        }
                    }
                }
            }
        } else {

            $conditions = array( 'course' => $courseid );
            $sections = $DB->get_records('course_sections', $conditions);

            $un_gamified = false;
            foreach ($sections as $section) {

                $sec_gamificada = $DB->get_record('gmdl_seccion_curso', array(
                    'mdl_id_seccion_curso' => $section->id,
                ));

                if ($sec_gamificada) {
                    $un_gamified = true;

                    $conditions = array('mdl_id_seccion_curso' => $section->id);
                    local_gamedlemaster_log::info(
                        "Delete gamified section {$section->id}");

                    $DB->delete_records('gmdl_seccion_curso', $conditions);


                    $conditions = array('gmdl_id_seccion_curso' => $sec_gamificada->id);
                    local_gamedlemaster_log::info(
                        "Deleted recompenses for gamified section {$sec_gamificada->id}");

                    $DB->delete_records('gmdl_recompensas_seccion', $conditions);
                }
            }

            if ($un_gamified) {
                local_gamedlemaster_log::success(
                    "Deleted experience points support for course $courseid");
            }
        }

    }


    public static function module_completion_updated(
      core\event\course_module_completion_updated $event) {

        global $DB;
        global $USER;
        $eventdata = $event->get_data();
        $courseid = $eventdata['courseid'];
        $format   = course_get_format($courseid);

        if ($format->get_format() === "gamedle") {

            $conditions = array( 'course' => $courseid );
            $modcompletion = $DB->get_record('course_modules_completion',
                array( 'id' => $eventdata['objectid'] )
            );

            $module = $DB->get_record('course_modules',
                array( 'id' => $modcompletion->coursemoduleid ),
                'section'
            );

            $section = $DB->get_record('course_sections',
                array( 'id' => $module->section )
            );

            // y por cada seccion que no sea la seccion 0 
            if ($section->section != 0) {
                $conditions = array( 'section' => $section->id );
                $modules = $DB->get_records('course_modules', $conditions);

                // Revisa si ya completo todas las actividades de dicha
                // seccion
                $completed = true;
                foreach($modules as $module) {

                    $conditions = array(
                        'coursemoduleid' => $module->id,
                        'userid' => $USER->id
                    );

                    $completion = $DB->get_record('course_modules_completion',
                        $conditions
                    );

                    $completed = $completed && (boolean)$completion;
                    $completed = $completed && $completion->completionstate;
                }

                if($completed) {
                    $format->bring_xp($USER->id,$section->id);
                }
            }
        }
    }
}
    

