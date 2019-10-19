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
    $CATEGORY = 'block_gmxp_category';

    /**
     * Creación de la pantalla para los settings generales
     *  Ref: CU-E02-1: Configuración General del módulo de experiencia
     *  Docs: lib/adminlib.php
     */
    $heading = new admin_setting_heading( $PLUGIN.'/header',
        get_string('SETTINGS_GENERAL_HEADER', $PLUGIN),
        get_string('SETTINGS_GENERAL_HEADER_DESC', $PLUGIN));

    $enabledCheckbox = new admin_setting_configcheckbox( $PLUGIN.'/'.
        get_string('SYS_SETTINGS_GENERAL_ACTIVATED', $PLUGIN),
        get_string('SETTINGS_GENERAL_ENABLED', $PLUGIN),
        get_string('SETTINGS_GENERAL_ENABLED_DESC', $PLUGIN),
        true, 1, 0);

    $eventsCheckbox = new admin_setting_configcheckbox( $PLUGIN.'/'.
        get_string('SYS_SETTINGS_GENERAL_EVENTS', $PLUGIN),
        get_string('SETTINGS_GENERAL_EVENTS_ENABLED', $PLUGIN),
        get_string('SETTINGS_GENERAL_EVENTS_ENABLED_DESC', $PLUGIN),
        true, 1, 0);

    $general_settings = new admin_settingpage(
        get_string('SYS_SETTINGS_GENERAL'),
        get_string('SETTINGS_GENERAL', $PLUGIN)
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
        get_String('SYS_SETTINGS_VISUAL', $PLUGIN),
        get_string('SETTINGS_VISUAL', $PLUGIN),
        new moodle_url('/blocks/gmxp/settings/visual_settings.php')
    );

    /**
     * Creación de la pantalla para los settings del esquema de experiencia
     *  Ref: CU-E02-2: Configurar Esquema de Experiencia
     *  Docs: lib/adminlib.php
     */
    $scheme_settings = new admin_externalpage(
        get_string('SYS_SETTINGS_SCHEME', $PLUGIN),
        get_string('SETTINGS_SCHEME', $PLUGIN),
        new moodle_url('/blocks/gmxp/settings/scheme_settings.php')
    );

    /**
     * Creación de la pantalla para los settings del esquema de experiencia
     *  Ref: CU-E02-2: Configurar Esquema de Experiencia
     *  Docs: lib/adminlib.php
     */
    $events_settings = new admin_externalpage(
        get_string('SYS_SETTINGS_EVENTS', $PLUGIN),
        get_string('SETTINGS_EVENTS', $PLUGIN),
        new moodle_url('/blocks/gmxp/settings/events_settings.php')
    );

    /**
     * Vinculación de los menus de configuración con la categoría del menú
     *  Ref: CU-E02-2: Configurar Esquema de Experiencia
     *  Docs: lib/adminlib.php
     */
    $settings = new admin_category($CATEGORY, get_string('SETTINGS_CATEGORY', $PLUGIN));
    $settings->add($CATEGORY, $general_settings);
    $settings->add($CATEGORY, $visual_settings);
    $settings->add($CATEGORY, $scheme_settings);
    $settings->add($CATEGORY, $events_settings);


