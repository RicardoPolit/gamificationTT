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
            
        $courseid = $event->get_data()['courseid'];
        $course   = get_course($courseid);

        /* if (course->isGamified) */
        if( $course->format === "gamedle" ){

            $course = course_get_format($course)->get_course();
            if( $course->xpEnabled == 1 ){
                $_SESSION['Gamedle']['format'] = "Yes it Gamified and enabled";
            }
            else
                $_SESSION['Gamedle']['format'] = "";
        }
        else
            $_SESSION['Gamedle']['format'] = "";

        /* TODO  
         *   Handle event
         */
    }
}



