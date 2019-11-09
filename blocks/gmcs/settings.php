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

    $PLUGIN = 'block_gmcs';

    /**
     * Creación de la pantalla para los settings generales
     *  Docs: lib/adminlib.php
     */
    $heading = new admin_setting_heading( $PLUGIN.'/header',
        get_string('SETTINGS_GENERAL_HEADER', $PLUGIN),
        get_string('SETTINGS_GENERAL_HEADER_DESC', $PLUGIN));

    $enabledCheckbox = new admin_setting_configcheckbox(
        $PLUGIN.'/'.block_gmcs_core::ACTIVATED,
        get_string('SETTINGS_GENERAL_ENABLED', $PLUGIN),
        get_string('SETTINGS_GENERAL_ENABLED_DESC', $PLUGIN),
        true, 1, 0);

    $general_settings = new admin_settingpage(
        block_gmcs_core::SETTINGS_GENERAL, get_string('SETTINGS_GENERAL', $PLUGIN)
    );

    $general_settings->add($heading);
    $general_settings->add($enabledCheckbox);

    /**
     * Creación de la pantalla para los settings visuales
     *  Docs: lib/adminlib.php
     */
    $scheme_settings = new admin_externalpage(
        block_gmcs_core::SETTINGS_SCHEME,
        get_string('SETTINGS_SCHEME', $PLUGIN),
        new moodle_url(block_gmcs_core::PATH_SETTINGS_VISUAL)
    );


    /**
     * Vinculación de los menus de configuración con la categoría del menú
     *  Docs: lib/adminlib.php
     */
    $settings = new admin_category(block_gmcs_core::CATEGORY,
        get_string('SETTINGS_CATEGORY', $PLUGIN));

    $settings->add(block_gmcs_core::CATEGORY, $general_settings);
    $settings->add(block_gmcs_core::CATEGORY, $scheme_settings);
?>
