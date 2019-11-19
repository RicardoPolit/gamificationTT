<?php
$string['pluginname'] = 'Gamedle Currency';
$string['gmcs'] = $string['pluginname'];
$string['gmcs:addinstance']   = "Add a new {$string['pluginname']} block";
$string['gmcs:myaddinstance'] = "Add a new {$string['pluginname']} block to the My Moodle page";

// ==== SETTINGS =====================================
$string['SETTINGS_CATEGORY'] = "Gamedle: Finance Module";
$string['SETTINGS_GENERAL'] = "General Setting Up";
$string['SETTINGS_SCHEME']  = "Currency Setting Up";

// ==== GENERAL SETTINGS =====================================
$string['SETTINGS_GENERAL_HEADER'] = "Choose the main features";
$string['SETTINGS_GENERAL_HEADER_DESC'] = "TODO: Write header description";
$string['SETTINGS_GENERAL_ENABLED'] = "Enable the Currency System ";
$string['SETTINGS_GENERAL_ENABLED_DESC'] = "TODO: Write the enabled description";

// ==== SCHEME SETTINGS =====================================
$string['SETTINGS_SCHEME_HEADER'] = "Customize the currency system";
$string['SETTINGS_SCHEME_DESC'] =
    "Control how does the currency system works. Specify the equivalence rules
    between silver coins and gold coins.";

$string['SCHEME_SETTING_TEXT_SILVER'] = "Gold coin cost";
$string['SCHEME_SETTING_HELP_SILVER'] = "the {$string['SCHEME_SETTING_TEXT_SILVER']}";
$string['SCHEME_SETTING_HELP_SILVER_help'] =
    "Specify amount of silver coins required to buy a gold coin";

$string['SCHEME_SETTING_TEXT_CHECKBOX_ENABLED'] = "activar";

$string['SCHEME_SETTING_TEXT_CR_SUPPORT'] = "Dar monedas: ";

$string['SCHEME_SETTING_TEXT_WIN_USER_GROUP'] = "Competencia 1vs1 ganar evento";
$string['SCHEME_SETTING_HELP_WIN_USER_GROUP'] =
    "the {$string['SCHEME_SETTING_TEXT_WIN_USER_GROUP']}";
$string['SCHEME_SETTING_HELP_WIN_USER_GROUP_help'] =
    "Specify amount of silver coins given when user wins another user";

$string['SCHEME_SETTING_TEXT_WIN_SYSTEM_GROUP'] = "Competencia vs CPU ganar evento";
$string['SCHEME_SETTING_HELP_WIN_SYSTEM_GROUP'] =
    "the {$string['SCHEME_SETTING_TEXT_WIN_SYSTEM_GROUP']}";
$string['SCHEME_SETTING_HELP_WIN_SYSTEM_GROUP_help'] =
    "Specify amount of silver coins given when user wins agains system";

$string['SCHEME_SETTING_TEXT_QUESTION_GROUP'] = "Pregunta diaria correcta evento";
$string['SCHEME_SETTING_HELP_QUESTION_GROUP'] =
    "the {$string['SCHEME_SETTING_TEXT_QUESTION_GROUP']}";
$string['SCHEME_SETTING_HELP_QUESTION_GROUP_help'] =
    "Specify amount of silver coins given when user answers correctly 
    a daily question";

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
