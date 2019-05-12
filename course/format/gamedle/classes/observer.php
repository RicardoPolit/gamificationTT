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

class format_gamedle_observer {
    
    /** Variable sectionXP
     *  Stores array of courses recently created and their sections
     *  Usage:
     *      sectionXP[ courseid ] = num_sections;
     */
    private static $sectionsXP = array();
    
    private static function checkSession(){
        if(!isset($_SESSION['Gamedle']['format']))
            $_SESSION['Gamedle']['format'] = "";
    }
    
    public static function course_created(core\event\course_created $event){
    
        // Saving course id with sections
        /*$courseid = $event->get_data()['courseid'];
        format_gamedle_observer::$sectionsXP[$courseid] = -1;*/
    }
    
    public static function course_section_created(core\event\course_section_created $event){
        
        $courseid = $event->get_data()['courseid'];
        $section  = $event->get_data()['other']['sectionnum'];
        $format   = course_get_format($courseid);
        
        if( $format->get_format() === "gamedle" )
            if( $format->experienceEnabled() )
                $format->createSectionXP($section);
    }
}

    /*            
        $sections = $DB->get_records('course_sections',['course'=>$course->id]);
        $totalxp = (int)get_config('block_gmxp','firstExpGiven');
        
        if($numsec==1){}
        $numsec--; // excluding section 0
        $sectionx = floor($totalxp/$numsec);
        $sectionLast = $sectionx + ($totalxp - ($sectionx*$numsec));
    */
    

