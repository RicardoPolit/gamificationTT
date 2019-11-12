<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *
 *   PROJECT:     Gamedle
 *   PROFESSOR:   Sandra Bautista, Andres Catalan.
 *
 * DESARROLLADORES: Daniel Ortega (GitLab/Github @DanielOrtegaZ)
*/

defined('MOODLE_INTERNAL') || die();
$observers = array (
    array (
        'eventname' => '\local_gamedlemaster\event\gmcompcpu_compFinishedWon',
        'callback' => 'block_gmcs_observer::competencecpu_won'
    ),
    array (
        'eventname' => '\local_gamedlemaster\event\gmcompvs_compFinishedWon',
        'callback' => 'block_gmcs_observer::competencevs_won'
    ),
    array (
        'eventname' => '\local_gamedlemaster\event\gmcompvs_compFinishedReturnCon',
        'callback' => 'block_gmcs_observer::competencevsreturn_won'
    ),
    array (
        'eventname' => '\local_gamedlemaster\event\gmpregdiarias_pregCorrecta',
        'callback' => 'block_gmcs_observer::daily_question_answered'
    )
);
