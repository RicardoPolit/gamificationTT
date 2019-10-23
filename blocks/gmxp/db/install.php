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

    $PLUGIN = 'block_gmxp';

    /**
     * Set the default visual settings
     */
    function set_default_visual_settings() {

        local_gamedlemaster_log::info(
            "Setting the default values for visual settings", "GMXP");

        set_config(get_string('SYS_SETTINGS_VISUAL_TITLE', $PLUGIN),
                   get_string('VISUAL_SETTING_DEFAULT_TITLE', $PLUGIN), $PLUGIN);

        set_config(get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', $PLUGIN),
                   get_string('VISUAL_SETTING_DEFAULT_DESCRIPTION', $PLUGIN), $PLUGIN);

        set_config(get_string('SYS_SETTINGS_VISUAL_MESSAGE', $PLUGIN),
                   get_string('VISUAL_SETTING_DEFAULT_MESSAGE', $PLUGIN), $PLUGIN);

        set_config(get_string('SYS_SETTINGS_VISUAL_COLORLVL', $PLUGIN),
                   get_string('VISUAL_SETTING_DEFAULT_COLORLVL', $PLUGIN), $PLUGIN);

        set_config(get_string('SYS_SETTINGS_VISUAL_COLORBAR', $PLUGIN),
                   get_String('VISUAL_SETTING_DEFAUT_COLORBAR', $PLUGIN), $PLUGIN);

        set_config(get_string('SYS_SETTINGS_VISUAL_IMAGE', $PLUGIN),
                   get_string('VISUAL_SETTINGS_DEFAULT_IMAGE', $PLUGIN), $PLUGIN);
    }

    function set_default_scheme_settings() {
    }

    function set_default_events_Settings() {
    }

    /**
     * Function that is called when installing
     */ 
    function xmldb_block_gmxp_install() {

        set_default_visual_settings();
        set_default_scheme_settings();
        set_default_events_settings();
    }
?>
