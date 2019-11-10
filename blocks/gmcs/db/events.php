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
        'callback' => 'block_gmxp_observer::competence_won'
    )
);
