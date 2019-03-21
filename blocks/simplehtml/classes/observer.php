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
session_start();

class block_simplehtml_observer {

    public static function update(\core\event\base $event) {
        // This code works when put inside the corresponding 'store' function in blocks/recent_activity/classes/observer.php
        
        if(!isset($_SESSION['Gamedle']['tmp'])){
            $_SESSION['Gamedle'] = array();
            $_SESSION['Gamedle']['tmp'] = "";
        }
        $_SESSION['Gamedle']['user_lvl'] = 3;
        $_SESSION['Gamedle']['user_xp'] = 300;
        
        $json_data = json_encode($event->get_data());
        $_SESSION['Gamedle']['tmp'] .= "EVENT: \".$json_data.\"";
        
        echo("<script>console.log('EVENT: ".$_SESSION['Gamedle']['tmp']."');</script>");
    }
}
