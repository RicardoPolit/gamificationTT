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
        'eventname' => 'mod_quiz\event\attempt_submitted',
        'callback'  => 'block_gmxp_observer::attempt_submitted',
    ),
    array (
        'eventname' => 'core\event\assessable_submitted',
        'callback'  => 'block_gmxp_observer::assessable_submittedC',
    ),
    array (
        'eventname' => 'mod_assign\event\assessable_submitted',
        'callback'  => 'block_gmxp_observer::assessable_submittedM',
    ),
    array (
        'eventname' => 'mod_choice\event\answer_submitted',
        'callback'  => 'block_gmxp_observer::answer_submitted',
    ),
    array (
        'eventname' => 'mod_feedback\event\response_submitted',
        'callback'  => 'block_gmxp_observer::response_submitted',
    ),
    array (
        'eventname' => 'core\event\course_completed',
        'callback'  => 'block_gmxp_observer::course_completed',
    ),
);

?>
