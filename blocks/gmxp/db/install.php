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

    /**
     * Set the default visual settings
     */
    function set_default_visual_settings($PLUGIN) {

        local_gamedlemaster_log::info(
            "Setting the default values for visual settings", "GMXP");

        set_config(block_gmxp_core::TITLE,
            get_string('VISUAL_SETTING_DEFAULT_TITLE', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::DESCRIPTION,
            get_string('VISUAL_SETTING_DEFAULT_DESCRIPTION', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::MESSAGE,
            get_string('VISUAL_SETTING_DEFAULT_MESSAGE', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::COLORLVL,
            get_string('VISUAL_SETTING_DEFAULT_COLORLVL', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::COLORBAR,
            get_String('VISUAL_SETTING_DEFAULT_COLORBAR', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::IMAGE,
            get_string('VISUAL_SETTING_DEFAULT_IMAGE', $PLUGIN), $PLUGIN);
    }

    function set_default_scheme_settings($PLUGIN) {
        local_gamedlemaster_log::info(
            "Setting the default values for scheme settings", "GMXP");

        set_config(block_gmxp_core::INCREMENT,
            get_string('SCHEME_SETTING_DEFAULT_INCREMENT', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::VALUE,
            get_string('SCHEME_SETTING_DEFAULT_VALUE', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::LEVELXP,
            get_string('SCHEME_SETTING_DEFAULT_LEVELXP', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::COURSEXP,
            get_string('SCHEME_SETTING_DEFAULT_COURSEXP', $PLUGIN), $PLUGIN);
    }

    function set_default_events_settings($PLUGIN) {
        local_gamedlemaster_log::info(
            "Setting the default values for scheme settings", "GMXP");

        set_config(block_gmxp_core::COMPETENCECPU,
            get_string('EVENTS_SETTING_DEFAULT_COMPCPU', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::COMPETENCECPUXP,
            get_string('EVENTS_SETTING_DEFAULT_COMPCPUXP', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::COMPETENCEVS,
            get_string('EVENTS_SETTING_DEFAULT_COMPVS', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::COMPETENCEVSXP,
            get_string('EVENTS_SETTING_DEFAULT_COMPVSXP', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::PREGUNTADIARIA,
            get_string('EVENTS_SETTING_DEFAULT_PREGDIARIA', $PLUGIN), $PLUGIN);

        set_config(block_gmxp_core::PREGUNTADIARIAXP,
            get_string('EVENTS_SETTING_DEFAULT_PREGDIARIAXP', $PLUGIN), $PLUGIN);
    }

    /**
     * Function that is called when installing
     */
    function xmldb_block_gmxp_install() {

        $PLUGIN = 'block_gmxp';
        set_default_visual_settings($PLUGIN);
        set_default_scheme_settings($PLUGIN);
        set_default_events_settings($PLUGIN);
    }
?>
