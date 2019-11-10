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

    /**
     * This function sets up the default settings for
     * the configuration of the cyrrency scheme settings
     */
    function set_default_scheme_settings($PLUGIN) {

        set_config(block_gmcs_core::SILVER_TO_GOLD,
            get_string('SCHEME_SETTING_DEFAULT_SILVER', $PLUGIN), $PLUGIN);

        set_config(block_gmcs_core::DEFEAT_SYSTEM,
            get_string('SCHEME_SETTING_DEFAULT_WIN_SYSTEM', $PLUGIN), $PLUGIN);

        set_config(block_gmcs_core::DEFEAT_USER,
            get_string('SCHEME_SETTING_DEFAULT_WIN_USER', $PLUGIN), $PLUGIN);

        local_gamedlemaster_log::success(
            'established default scheme settings', 'GMCS');
    }

    /**
     * Function that is called by moodle when right after the
     * installation of this plugin.
     */ 
    function xmldb_block_gmcs_install() {

        $PLUGIN = 'block_gmcs';
        set_default_scheme_settings($PLUGIN);
    }
?>
