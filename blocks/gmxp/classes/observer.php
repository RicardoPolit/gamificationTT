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

function escapeJS($input){
        $input = str_replace("\\","\\\\",$input);
        $input = str_replace("\n"," * ",$input);
        return $input;
}

class block_gmxp_observer {

    private static function createSessionValues(){

        if(!isset($_SESSION['Gamedle']))
            $_SESSION['Gamedle'] = array();
        
        if(!isset($_SESSION['Gamedle']['AS']))
            $_SESSION['Gamedle']['AS'] = "";
            
        if(!isset($_SESSION['Gamedle']['CC']))
            $_SESSION['Gamedle']['CC'] = "";
            
        if(!isset($_SESSION['Gamedle']['CCU']))
            $_SESSION['Gamedle']['CCU']  = "";
            
            /*global $USER;
            global $DB;
            $result = $DB->get_record('gmdl_usuario',[ 'mdl_id_usuario' => $USER->id]);
            $_SESSION['Gamedle']['user_xp'] = $result['experiencia_actual'];
            $_SESSION['Gamedle']['user_lvl'] = $result['nivel_actual'];*/

            //$_SESSION['Gamedle']['xp_por_dar'] = 100;
            //$json_data = json_encode($event->get_data());

            //$_SESSION['Gamedle']['CC'] .= " * ".escapeJS($event->get_data()['eventname']);
    }

    public static function update(\mod_quiz\event\attempt_submitted $event) {
        // This code works when put inside the corresponding 'store' function in blocks/recent_activity/classes/observer.php

        block_gmxp_observer::createSessionValues();
        $_SESSION['Gamedle']['AS'] .= "\\\\".json_encode($event->get_data())." * ";
    }

    public static function courseCompleted(\core\event\base $event){
        block_gmxp_observer::createSessionValues();
        $_SESSION['Gamedle']['CC'] .= "\\\\".json_encode($event->get_data());
    }

    public static function courseCompletionUpdated(\core\event\course_completion_updated $event){
        block_gmxp_observer::createSessionValues();
        $_SESSION['Gamedle']['CCU'] .= "\\\\".json_encode($event->get_data());
    }

}
