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

    public static function attempt_submitted(\mod_quiz\event\attempt_submitted $event){
        block_gmxp_observer::createSessionValues();
        $_SESSION['Gamedle']['AttemptS'] .= "\\\\".json_encode($event->get_data())." * ";
    }
    
    public static function assessable_submittedC(core\event\assessable_submitted $event){
        block_gmxp_observer::createSessionValues();
        $_SESSION['Gamedle']['AssesableC'] .= "\\\\".json_encode($event->get_data())." * ";
    }
    
    public static function assessable_submittedM(\mod_assign\event\assessable_submitted $event){
        block_gmxp_observer::createSessionValues();
        $_SESSION['Gamedle']['AssesableM'] .= "\\\\".json_encode($event->get_data())." * ";
    }
    
    public static function answer_submitted(\mod_choice\event\answer_submitted $event){
        block_gmxp_observer::createSessionValues();
        $_SESSION['Gamedle']['AnswerS'] .= "\\\\".json_encode($event->get_data())." * ";
    }
    
    public static function response_submitted(\mod_feedback\event\response_submitted $event){
        block_gmxp_observer::createSessionValues();
        $_SESSION['Gamedle']['Response'] .= "\\\\".json_encode($event->get_data())." * ";
    }
    
    public static function course_completed(\core\event\course_completed $event){
        //block_gmxp_observer::createSessionValues();
        $fp = fopen('proof.txt', 'a+');
        fwrite($fp, "\\\\".json_encode($event->get_data())." * " );
        fclose($fp);
    }

    private static function createSessionValues(){

        if(!isset($_SESSION['Gamedle']))
            $_SESSION['Gamedle'] = array();
        
        if(!isset($_SESSION['Gamedle']['AttemptS']))   $_SESSION['Gamedle']['AttemptS']   = "";
        if(!isset($_SESSION['Gamedle']['AssesableC'])) $_SESSION['Gamedle']['AssesableC'] = "";
        if(!isset($_SESSION['Gamedle']['AssesableM'])) $_SESSION['Gamedle']['AssesableM'] = "";
        if(!isset($_SESSION['Gamedle']['AnswerS']))    $_SESSION['Gamedle']['AnswerS']    = "";
        if(!isset($_SESSION['Gamedle']['Response']))   $_SESSION['Gamedle']['Response']   = "";
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



