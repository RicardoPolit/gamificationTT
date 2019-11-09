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

    public static function competence_won(
      \local_gamedlemaster\event\gmcompcpu_compFinishedWon $event) {

        local_gamedle_log::info(
          'Event triggered', 'block_gmxp_observer::competence_won');
    }
    
    public static function module_completion_updated(core\event\course_module_completion_updated $event){
        
        global $USER;
        $courseGamified = true;
        if( $courseGamified  &&  $event->relateduserid == $USER->id ){
        
            $eventdata = $event->get_data();
            local_gamedlemaster_log::info($eventdata);
            if(isset($eventdata['other']['completionstate']) && $eventdata['other']['completionstate'] == 1){

            }
        }
    }
    
    public static function course_completed(core\event\course_completed $event){
        $fp = fopen('proof.txt', 'a+');
        fwrite($fp, "\\\\".json_encode($event->get_data())." * " );
        fclose($fp);
    }
    
    // Used with 
    // $_SESSION['Gamedle']['CC'] .= "\\\\".json_encode($event->get_data());
    private static function escapeJS($input){
        $input = str_replace("\\","\\\\",$input);
        $input = str_replace("\n"," * ",$input);
        return $input;
    }
}



