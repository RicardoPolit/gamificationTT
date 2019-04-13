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
        'callback'  => 'block_gmxp_observer::update',
    ),
    array (
        'eventname' => 'core\event\course_completed',
        'callback'  => 'block_gmxp_observer::courseCompleted',
        'internal'  => false
    ),
    array (
        'eventname' => 'core\event\course_completion_updated',
        'callback'  => 'block_gmxp_observer::courseCompletionUpdated',
    )
);

?>
