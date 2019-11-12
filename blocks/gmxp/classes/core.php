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

class block_gmxp_core {

    const CATEGORY = 'block_gmxp_category';

    // GENERAL SETTINGS
    const SETTINGS_GENERAL = "block_gmxp_general_settings";
    const ACTIVATED = "activated";
    const EVENTS = "events";

    // VISUAL SETTINGS
    const SETTINGS_VISUAL = "block_gmxp/visual_settings";
    const PATH_SETTINGS_VISUAL = "/blocks/gmxp/settings/visual_settings.php";

    const TITLE = "title";
    const DESCRIPTION = "description";
    const MESSAGE = "message";
    const COLORLVL = "colorLvl";
    const COLORBAR = "colorBar";
    const IMAGE = "image";
    const PATH_IMAGE = "/blocks/gmxp/pix/";
    const RESERVED_IMAGE_NAME = "icon.png";

    // SCHEME SETTINGS
    const SETTINGS_SCHEME = "block_gmxp/scheme_settings";
    const PATH_SETTINGS_SCHEME = '/blocks/gmxp/settings/scheme_settings.php';
    const INCREMENT = "increment";
    const VALUE = "incrementValue";
    const LEVELXP = "levelXP";
    const COURSEXP = "courseXP";

    // Increment types
    const LINEAL = '0';
    const PERCENTUAL = '1';

    const SETTINGS_EVENTS = "block_gmxp/events_settings";
    const PATH_SETTINGS_EVENTS = "/blocks/gmxp/settings/events_settings.php";

    const COMPETENCECPU = 'competencecpuevent';
    const COMPETENCECPUXP = 'competencecpuxp';
    const COMPETENCECPUGROUP = 'competencecpugroup';

    const COMPETENCEVS = 'competencevsevent';
    const COMPETENCEVSXP = 'competencevsxp';
    const COMPETENCEVSGROUP = 'competencevsgroup';

    const PREGUNTADIARIA = 'preguntadiariaevent';
    const PREGUNTADIARIAXP = 'preguntadiariaxp';
    const PREGUNTADIARIAGROUP = 'preguntadiariagroup';

    // VALUES FOR MOODLE PROFILE PAGE
    const PERFIL_EXPERIENCE = 'xp_node';
}
?>
