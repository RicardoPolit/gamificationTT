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
$observers = array (
    array (
        'eventname' => 'core\event\course_created',
        'callback'  => 'format_gamedle_observer::course_created',
        'internal'  => false,
    ),
    array (
        'eventname' => 'core\event\course_section_created',
        'callback'  => 'format_gamedle_observer::course_section_created',
        'internal'  => false,
    ),
);

?>
