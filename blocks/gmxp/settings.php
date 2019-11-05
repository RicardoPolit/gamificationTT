<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *   
 * TRABAJO TERMINAL: 2018-B029
 *   "GAMIFICACIÓN EN UNA PLATAFORMA WEB DE APRENDIZAJE"
 *
 *
 * ASESORES: Sandra I, Bautista, Edgar A. Catatán
 *
 * DESARROLLADORES:
 *   - Daniel Ortega  (gitlab: DanielOrtegaZ)
 *   - Ricardo, David
 *
*/

defined('MOODLE_INTERNAL') || die();

    $PLUGIN = 'block_gmxp';

    /**
     * Creación de la pantalla para los settings generales
     *  Ref: CU-E02-1: Configuración General del módulo de experiencia
     *  Docs: lib/adminlib.php
     */
    $heading = new admin_setting_heading( $PLUGIN.'/header',
        get_string('SETTINGS_GENERAL_HEADER', $PLUGIN),
        get_string('SETTINGS_GENERAL_HEADER_DESC', $PLUGIN));

    $enabledCheckbox = new admin_setting_configcheckbox(
        $PLUGIN.'/'.block_gmxp_core::ACTIVATED,
        get_string('SETTINGS_GENERAL_ENABLED', $PLUGIN),
        get_string('SETTINGS_GENERAL_ENABLED_DESC', $PLUGIN),
        true, 1, 0);

    $eventsCheckbox = new admin_setting_configcheckbox(
        $PLUGIN.'/'.block_gmxp_core::EVENTS,
        get_string('SETTINGS_GENERAL_EVENTS_ENABLED', $PLUGIN),
        get_string('SETTINGS_GENERAL_EVENTS_ENABLED_DESC', $PLUGIN),
        true, 1, 0);

    $general_settings = new admin_settingpage(
        block_gmxp_core::SETTINGS_GENERAL, get_string('SETTINGS_GENERAL', $PLUGIN)
    );

    $general_settings->add($heading);
    $general_settings->add($enabledCheckbox);
    $general_settings->add($eventsCheckbox);

    /**
     * Creación de la pantalla para los settings visuales
     *  Ref: CU-E02-2: Configurar Visualización de niveles
     *  Docs: lib/adminlib.php
     */
    $visual_settings = new admin_externalpage(
        block_gmxp_core::SETTINGS_VISUAL,
        get_string('SETTINGS_VISUAL', $PLUGIN),
        new moodle_url(block_gmxp_core::PATH_SETTINGS_VISUAL)
    );

    /**
     * Creación de la pantalla para los settings del esquema de experiencia
     *  Ref: CU-E02-2: Configurar Esquema de Experiencia
     *  Docs: lib/adminlib.php
     */
    $scheme_settings = new admin_externalpage(
        block_gmxp_core::SETTINGS_SCHEME,
        get_string('SETTINGS_SCHEME', $PLUGIN),
        new moodle_url(block_gmxp_core::PATH_SETTINGS_SCHEME)
    );

    /**
     * Creación de la pantalla para los settings del esquema de experiencia
     *  Ref: CU-E02-2: Configurar Esquema de Experiencia
     *  Docs: lib/adminlib.php
     */
    $events_settings = new admin_externalpage(
        block_gmxp_core::SETTINGS_EVENTS,
        get_string('SETTINGS_EVENTS', $PLUGIN),
        new moodle_url(block_gmxp_core::PATH_SETTINGS_EVENTS)
    );

    /**
     * Vinculación de los menus de configuración con la categoría del menú
     *  Ref: CU-E02-2: Configurar Esquema de Experiencia
     *  Docs: lib/adminlib.php
     */
    $settings = new admin_category(block_gmxp_core::CATEGORY,
        get_string('SETTINGS_CATEGORY', $PLUGIN));

    $settings->add(block_gmxp_core::CATEGORY, $general_settings);
    $settings->add(block_gmxp_core::CATEGORY, $visual_settings);
    $settings->add(block_gmxp_core::CATEGORY, $scheme_settings);
    $settings->add(block_gmxp_core::CATEGORY, $events_settings);
