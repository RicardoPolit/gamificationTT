<?php
$string['pluginname'] = 'Finanzas Gamedle';
$string['gmcs'] = $string['pluginname'];
$string['gmcs:addinstance']   = "Agrega un nuevo {$string['pluginname']} bloque";
$string['gmcs:myaddinstance'] = "Agrega u  nuevo {$string['pluginname']} bloque a  Mi página de Moodle";

// ==== SETTINGS =====================================
$string['SETTINGS_CATEGORY'] = "Gamedle: Módulo financiero";
$string['SETTINGS_GENERAL'] = "Configuración general";
$string['SETTINGS_SCHEME']  = "Configuración financiera";

// ==== GENERAL SETTINGS =====================================
$string['SETTINGS_GENERAL_HEADER'] = "Elige las características principales";
$string['SETTINGS_GENERAL_HEADER_DESC'] = "Configura si el complemento está activo o no";
$string['SETTINGS_GENERAL_ENABLED'] = "Activar complemento financiero ";
$string['SETTINGS_GENERAL_ENABLED_DESC'] = "Al activar el complemento financiero, las opciones de personalización del perfil gamificado estarán bloqueadas hasta ser comparadas";

// ==== SCHEME SETTINGS =====================================
$string['SETTINGS_SCHEME_HEADER'] = "Personaliza el sistema financiero";
$string['SETTINGS_SCHEME_DESC'] =
    "Controla ccómo el sistema financiero funciona. Establece la equivalencia de las monedas oro y plata.";

$string['SCHEME_SETTING_TEXT_SILVER'] = "Número de monedas de plata por una de oro";
$string['SCHEME_SETTING_HELP_SILVER'] = "El {$string['SCHEME_SETTING_TEXT_SILVER']}";
$string['SCHEME_SETTING_HELP_SILVER_help'] =
    "Especifica CUántas monedas de plata se necesitan par adquirir una de oro";

$string['SCHEME_SETTING_TEXT_CHECKBOX_ENABLED'] = "Activar";

$string['SCHEME_SETTING_TEXT_CR_SUPPORT'] = "Dar monedas: ";

$string['SCHEME_SETTING_TEXT_WIN_USER_GROUP'] = "Competencia 1vs1 ganar evento";
$string['SCHEME_SETTING_HELP_WIN_USER_GROUP'] =
    "El {$string['SCHEME_SETTING_TEXT_WIN_USER_GROUP']}";
$string['SCHEME_SETTING_HELP_WIN_USER_GROUP_help'] =
    "Especifica la cantidad de monedas de plata que se dan cuando un usuario derrota a otro";

$string['SCHEME_SETTING_TEXT_WIN_SYSTEM_GROUP'] = "Competencia vs sistema ganar evento";
$string['SCHEME_SETTING_HELP_WIN_SYSTEM_GROUP'] =
    "El {$string['SCHEME_SETTING_TEXT_WIN_SYSTEM_GROUP']}";
$string['SCHEME_SETTING_HELP_WIN_SYSTEM_GROUP_help'] =
    "Especifica cuántas monedas de plata se le dan a un usuario por derrotar al sistema";

$string['SCHEME_SETTING_TEXT_QUESTION_GROUP'] = "Pregunta diaria correcta evento";
$string['SCHEME_SETTING_HELP_QUESTION_GROUP'] =
    "La {$string['SCHEME_SETTING_TEXT_QUESTION_GROUP']}";
$string['SCHEME_SETTING_HELP_QUESTION_GROUP_help'] =
    "Especifica la cantidad de monedas de plata se otorgan al responder una pregunta diaria correctamente";

$string['SCHEME_SETTING_DEFAULT_SILVER'] = "1000";
$string['SCHEME_SETTING_DEFAULT_WIN_SYSTEM'] = "100";
$string['SCHEME_SETTING_DEFAULT_WIN_USER'] = "200";
$string['SCHEME_SETTING_DEFAULT_QUESTION'] = "100";

$string['SCHEME_SETTING_DEFAULT_WIN_SYSTEM_ENABLED'] = "1";
$string['SCHEME_SETTING_DEFAULT_WIN_USER_ENABLED'] = "1";
$string['SCHEME_SETTING_DEFAULT_QUESTION_ENABLED'] = "1";

// === FORM|SETTINGS ERRORS ==========================================
$string['SETTINGS_CURRENCY_DISABLED_REDIRECT'] = "Enabled Module";
$string['SETTINGS_CURRENCY_DISABLED'] =
    "The currency module is deactivated in the *{$string['SETTINGS_GENERAL']}* page 
    if you want to access this configuration you must enable the currency module";

$string['currency'] = "The value must be an interger greater than 0";

// ===== NOTIFICATION MESSAGES =========================================
$string['messageprovider:coinsgiven'] = 'Notificacion de tus monedas recibidas';

$string['coinsgivensubject'] = '{$a->nombre} has recibido {$a->monedas} monedas!';
$string['coinsgivenbody'] = 'Hola {$a->nombre}

Obtuviste monedas en la {$a->typecomp} por {$a->razon} 

Cantidad de monedas ganadas: {$a->monedas}';

$string['coinsgivensmall'] = 'Recibiste {$a->monedas} monedas!';
?>