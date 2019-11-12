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

        set_config(block_gmcs_core::ANSWER_QUESTION,
            get_string('SCHEME_SETTING_DEFAULT_QUESTION', $PLUGIN), $PLUGIN);

        set_config(block_gmcs_core::DEFEAT_SYSTEM_ENABLED,
            get_string('SCHEME_SETTING_DEFAULT_WIN_SYSTEM_ENABLED', $PLUGIN), $PLUGIN);

        set_config(block_gmcs_core::DEFEAT_USER_ENABLED,
            get_string('SCHEME_SETTING_DEFAULT_WIN_USER_ENABLED', $PLUGIN), $PLUGIN);

        set_config(block_gmcs_core::ANSWER_QUESTION_ENABLED,
            get_string('SCHEME_SETTING_DEFAULT_QUESTION_ENABLED', $PLUGIN), $PLUGIN);

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
