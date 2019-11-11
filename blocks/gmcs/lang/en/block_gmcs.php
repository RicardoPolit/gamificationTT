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

$string['SCHEME_SETTING_TEXT_WIN_USER'] = "Reward for win against user";
$string['SCHEME_SETTING_HELP_WIN_USER'] =
    "the {$string['SCHEME_SETTING_TEXT_WIN_USER']}";
$string['SCHEME_SETTING_HELP_WIN_USER_help'] =
    "Specify amount of silver coins given when user wins another user";

$string['SCHEME_SETTING_TEXT_WIN_SYSTEM'] = "Reward for win against system";
$string['SCHEME_SETTING_HELP_WIN_SYSTEM'] =
    "the {$string['SCHEME_SETTING_TEXT_WIN_SYSTEM']}";
$string['SCHEME_SETTING_HELP_WIN_SYSTEM_help'] =
    "Specify amount of silver coins given when user wins agains system";

$string['SCHEME_SETTING_TEXT_QUESTION'] = "Reward for answer daily question";
$string['SCHEME_SETTING_HELP_QUESTION'] =
    "the {$string['SCHEME_SETTING_TEXT_QUESTION']}";
$string['SCHEME_SETTING_HELP_QUESTION_help'] =
    "Specify amount of silver coins given when user answers correctly 
    a daily question";

$string['SCHEME_SETTING_DEFAULT_SILVER'] = "1000";
$string['SCHEME_SETTING_DEFAULT_WIN_SYSTEM'] = "1000";
$string['SCHEME_SETTING_DEFAULT_WIN_USER'] = "1000";
$string['SCHEME_SETTING_DEFAULT_QUESTION'] = "1000";

// === FORM|SETTINGS ERRORS ==========================================
$string['SETTINGS_CURRENCY_DISABLED_REDIRECT'] = "Enabled Module";
$string['SETTINGS_CURRENCY_DISABLED'] =
    "The currency module is deactivated in the *{$string['SETTINGS_GENERAL']}* page 
    if you want to access this configuration you must enable the currency module";

$string['currency'] = "The value must be an interger greater than 0";
