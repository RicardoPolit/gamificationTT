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
    
        if(!isset($_SESSION['Gamedle']['format'])){
            $_SESSION['Gamedle']['format'] = "";
        }
            
        $_SESSION['Gamedle']['format'] = "Gamedle Format Observer";
        /* TODO  
         *   Handle event
         */
    }
}



