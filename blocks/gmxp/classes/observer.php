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

    public static function update(\core\event\base $event) {
        // This code works when put inside the corresponding 'store' function in blocks/recent_activity/classes/observer.php

        if(!isset($_SESSION['Gamedle']['tmp'])){
            $_SESSION['Gamedle'] = array();
            $_SESSION['Gamedle']['tmp'] = escapeJS($event->get_data()['eventname']);



            global $USER;
            global $DB;
            $result = $DB->get_record('gmdl_usuario',[ 'mdl_id_usuario' => $USER->id]);
            $_SESSION['Gamedle']['user_xp'] = $result['experiencia_actual'];
            $_SESSION['Gamedle']['user_lvl'] = $result['nivel_actual'];

        }


        $_SESSION['Gamedle']['xp_por_dar'] = 100;
        
        $json_data = json_encode($event->get_data());
        echo("<script>console.log('EVENT: ".$_SESSION['Gamedle']['tmp']."');</script>");
    }

}
