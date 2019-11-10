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

class block_gmcs extends block_base {

    const PLUGIN = 'block_gmcs';

    public function init() {
        $this->title = get_string('gmcs', self::PLUGIN);
    }

    public function get_content() {
        return "Hello currency System";
    }

    public function has_config() { return true; }
    public function hide_header() { return false; }
}
?>
