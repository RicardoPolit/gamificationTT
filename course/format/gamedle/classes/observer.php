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
    
    public static function course_created(core\event\course_created $event){
    
        format_gamedle_observer::checkSession();
        $courseid = $event->get_data()['courseid'];
        $course   = get_course($courseid);

        if( $course->format === "gamedle" ){

            $course = course_get_format($course)->get_course();
            if( $course->xpEnabled == 1 ){
            
                global $DB; // lib/dml/moodle_database
                $sections = $DB->get_records('course_sections',['course'=>$course->id]);
                //$sections = $DB->get_records_select('course_sections', 'course = ?', array($course->id));
                //$sections = $DB->get_records_sql("SELECT id,section FROM {" . 'course_sections' . "} course = ?", array($course->id));
                
                $numsec = count($sections);
                $totalxp = (int)get_config('block_gmxp','firstExpGiven');
                
                if($numsec==1){
                    $_SESSION['Gamedle']['format'] = array(1,$sections,$totalxp);
                    return;
                }
                
                $numsec--; // excluding section 0
                $sectionx = floor($totalxp/$numsec);
                $sectionLast = $sectionx + ($totalxp - ($sectionx*$numsec));
                
                /** 
                 * TODO: Update records in the database
                 */
                $_SESSION['Gamedle']['format'] = array($sections,$sectionx,$sectionLast);
            }
            else
                $_SESSION['Gamedle']['format'] = "Debug: Format Gamedle but not enabled XP";
        }
        else
            $_SESSION['Gamedle']['format'] = "Debug: !Not Format Gamedle";

        /* TODO  
         *   Handle event
         */
    }
    
    private static function checkSession(){
        
        if(!isset($_SESSION['Gamedle']['format'])){
            $_SESSION['Gamedle']['format'] = "";
        }
    }
}



