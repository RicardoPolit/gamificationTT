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
        'eventname' => 'core\event\user_created',
        'callback'  => 'local_gamedlemaster_observer::create_gamified_user',
    ),
    array (
        'eventname' => 'core\event\user_deleted',
        'callback'  => 'local_gamedlemaster_observer::delete_gamified_user',
    ),
);
?>
