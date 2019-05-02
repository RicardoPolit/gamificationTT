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

class block_gmxp_observer {
    
    public static function module_completion_updated(core\event\course_module_completion_updated $event){
        block_gmxp_observer::createSessionValues();
        
        global $USER;
        $courseGamified = true;
        if( $courseGamified  &&  $event->relateduserid == $USER->id )
            if($event->other->completionstate == 1){
                /* TODO: Check if all activities are done.
                 *  If( allActivities.done ){
                 *     calculateSeccionXP();
                 *     $_SESSION['Gamedle']['XP']['Extra'] += $expSeccion;
                 *  }
                 */
            }
            
        //"\\\\".json_encode($event->get_data())." * ";
    }
    
    public static function course_completed(\core\event\course_completed $event){
        $fp = fopen('proof.txt', 'a+');
        fwrite($fp, "\\\\".json_encode($event->get_data())." * " );
        fclose($fp);
    }

    private static function createSessionValues(){
            
        if(!isset($_SESSION['Gamedle']['XP']['Extra'])){
            if(!isset($_SESSION['Gamedle']))
                $_SESSION['Gamedle'] = array();
                
            if(!isset($_SESSION['Gamedle']['XP']))
                $_SESSION['Gamedle']['XP'] = array();
                
            if(!isset($_SESSION['Gamedle']['XP']['Extra']))
                $_SESSION['Gamedle']['XP']['Extra'] = 0;
        }
        //if(!isset($_SESSION['Gamedle']['CourseC']))    $_SESSION['Gamedle']['CourseC']    = "";
    }
    
    // Used with 
    // $_SESSION['Gamedle']['CC'] .= "\\\\".json_encode($event->get_data());
    private static function escapeJS($input){
        $input = str_replace("\\","\\\\",$input);
        $input = str_replace("\n"," * ",$input);
        return $input;
    }
}



