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

class block_gmcs_observer {

    public static function competence_won(
      \local_gamedlemaster\event\gmcompcpu_compFinishedWon $event) {

        local_gamedle_log::info(
          'Event triggered', 'block_gmcs_observer::competence_won');
    }
}
?>
