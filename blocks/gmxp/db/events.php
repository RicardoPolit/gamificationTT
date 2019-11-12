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
        'eventname' => 'core\event\course_module_completion_updated',
        'callback'  => 'block_gmxp_observer::module_completion_updated',
    ),
    array (
        'eventname' => '\local_gamedlemaster\event\gmcompcpu_compFinishedWon',
        'callback' => 'block_gmxp_observer::competencecpu_won'
    ),
    array (
        'eventname' => '\local_gamedlemaster\event\gmcompvs_compFinishedWon',
        'callback' => 'block_gmxp_observer::competencevs_won'
    ),
    array (
        'eventname' => '\local_gamedlemaster\event\gmpregdiarias_pregCorrecta',
        'callback' => 'block_gmxp_observer::daily_question_answered'
    )
);

?>
