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

/**
 * Global strings variables displayed in different moodle pages
 * those pages are commonly access menus
 */

$string['pluginname'] = 'Gamedle Level';
$string['gmxp'] = $string['pluginname'];
$string['gmxp:addinstance']   = "Add a new {$string['pluginname']} block";
$string['gmxp:myaddinstance'] = "Add a new {$string['pluginname']} block to the My Moodle page";

/**
 * Global strings variables for the text and help displayed in
 * the different settings, those strings are grouped according
 * to the settings pages.
 */

// ==== SETTINGS MENU =======================================
$string['SETTINGS_CATEGORY'] = "Gamedle: Experience Module";
$string['SETTINGS_GENERAL'] = "General Setting Up";
$string['SETTINGS_VISUAL'] = "Visual Setting Up";
$string['SETTINGS_SCHEME']  = "Experience Setting Up";
$string['SETTINGS_EVENTS']  = "Events Setting Up";

// ==== GENERAL SETTINGS ====================================
$string['SETTINGS_GENERAL_HEADER'] = "Choose the main features";
$string['SETTINGS_GENERAL_HEADER_DESC'] = 
    "You can prevent the events to give experience points and/or disable the 
    experience system";

$string['SETTINGS_GENERAL_ENABLED'] = "Enable the experience system";
$string['SETTINGS_GENERAL_ENABLED_DESC'] =
    "You can prevent the events to give experience points and/or disable the 
    experience system";

$string['SETTINGS_GENERAL_EVENTS_ENABLED'] =
    "Enable the experience points in supported events";

$string['SETTINGS_GENERAL_EVENTS_ENABLED_DESC'] = 
    "Gives experience the default or established xp points for the list of events 
    supported";

// ==== VISUAL SETTINGS =====================================
$string['SETTINGS_VISUAL_HEADER'] = "Customize the levels looking";
$string['SETTINGS_VISUAL_DESC'] = 
    "You can specify the title, description and congratulations message of levels. 
    In addition you can set the image and colors of levels.";

$string['VISUAL_SETTING_TEXT_TITLE'] = "Levels Title";
$string['VISUAL_SETTING_HELP_TITLE'] = "the {$string['VISUAL_SETTING_TEXT_TITLE']}";
$string['VISUAL_SETTING_HELP_TITLE_help'] =
    "Specify the title of the levels displayed in the notification of new reached 
    level";

$string['VISUAL_SETTING_TEXT_MESSAGE'] = "Congratulations message";
$string['VISUAL_SETTING_HELP_MESSAGE'] = "the {$string['VISUAL_SETTING_TEXT_MESSAGE']}";
$string['VISUAL_SETTING_HELP_MESSAGE_help'] =
    "Specify the congratulations message displayed in the notification of new reached 
    level";

$string['VISUAL_SETTING_TEXT_DESCRIPTION'] = "Levels Description";
$string['VISUAL_SETTING_HELP_DESCRIPTION'] = "the {$string['VISUAL_SETTING_TEXT_DESCRIPTION']}";
$string['VISUAL_SETTING_HELP_DESCRIPTION_help'] =
    "Specify the levels description displayed in the notification of new reached 
    level";

$string['VISUAL_SETTING_TEXT_COLORLVL'] = "Levels color";
$string['VISUAL_SETTING_HELP_COLORLVL'] = "the {$string['VISUAL_SETTING_TEXT_COLORLVL']}";
$string['VISUAL_SETTING_HELP_COLORLVL_help'] =
    "Specify the levels color displayed in the block {$string['pluginname']}";

$string['VISUAL_SETTING_TEXT_COLORBAR'] = "Progress Bar Color";
$string['VISUAL_SETTING_HELP_COLORBAR'] = "the {$string['VISUAL_SETTING_TEXT_COLORBAR']}";
$string['VISUAL_SETTING_HELP_COLORBAR_help'] =
    "Specify the color of the progress bar displayed in the block 
    {$string['pluginname']}";

$string['VISUAL_SETTING_TEXT_IMAGE'] = "Levels Image";
$string['VISUAL_SETTING_HELP_IMAGE'] = "the {$string['VISUAL_SETTING_TEXT_IMAGE']}";
$string['VISUAL_SETTING_HELP_IMAGE_help'] =
    "Select the image shown in the block {$string['pluginname']}. If the image is not 
    provided, the image pix/image.jpg in the plugin's directory will be shown";

$string['VISUAL_SETTING_ERROR_IMAGE_NAME'] = "La imagen no puede ser llamada icon.png";

$string['VISUAL_SETTING_ERROR_IMAGE_SAVE'] =
    "Error ocurred. The image file couldn't be saved";

$string['VISUAL_SETTING_ERROR_IMAGE_DELETE'] =
    "Error ocurred. The previous image file cannot be deleted";

$string['VISUAL_SETTING_DEFAULT_TITLE'] = "Gamedle Levels";
$string['VISUAL_SETTING_DEFAULT_MESSAGE'] = "CONGRATULATIONS";
$string['VISUAL_SETTING_DEFAULT_DESCRIPTION'] = "New reached level";
$string['VISUAL_SETTING_DEFAULT_COLORLVL'] = "#0B619F";
$string['VISUAL_SETTING_DEFAULT_COLORBAR'] = "#0B619F";
$string['VISUAL_SETTING_DEFAULT_IMAGE'] = "image.jpg";

// ==== SCHEME SETTINGS =====================================
$string['SETTINGS_SCHEME_HEADER'] = "Customize the experience points system";
$string['SETTINGS_SCHEME_DESC'] = 
    "Control how does the required amount of experience points increases
    from level to level, set the experience points that the courses gives
    and specify the experience points given by the first level.";

$string['SCHEME_SETTING_TEXT_INCREMENT'] = "Increment Type";
$string['SCHEME_SETTING_HELP_INCREMENT'] = "the {$string['SCHEME_SETTING_TEXT_INCREMENT']}";
$string['SCHEME_SETTING_HELP_INCREMENT_help'] =
    "Specify the type of increment that the experience system should work with. If you specify
    the Lineal Increment Type the levels will increase that value. Otherwise if the increment
    type is percentual, the levels will augment of next level xp will increment the portion
    specified in that value.";

$string['SCHEME_SETTING_TEXT_LINEAL'] = "Lineal Increment";
$string['SCHEME_SETTING_HELP_LINEAL'] = "the {$string['SCHEME_SETTING_TEXT_LINEAL']}";
$string['SCHEME_SETTING_HELP_LINEAL_help'] = 
    "Indicates the lineal value in which the levels will increase. If level 'i' consists of
    'n' experience points and increment value is 'y', then the following level 'n+1' will consist
    on 'n+y' experience points";

$string['SCHEME_SETTING_TEXT_PERCENTUAL'] = "Percentual Increment";
$string['SCHEME_SETTING_HELP_PERCENTUAL'] = "the {$string['SCHEME_SETTING_TEXT_PERCENTUAL']}";
$string['SCHEME_SETTING_HELP_PERCENTUAL_help'] = 
    "Indicates the percentual value from 0.1 to 1.0 in which the levels increase. If the level 
    '1' consists of 'n' experience points and increment value is 'y', then the level 'i' 
    experience points will be determined by the following formula 'n*(1+y)^i'";

$string['SCHEME_SETTING_TEXT_LEVELXP'] = "First Level Experience Points";
$string['SCHEME_SETTING_HELP_LEVELXP'] = "the {$string['SCHEME_SETTING_TEXT_LEVELXP']}";
$string['SCHEME_SETTING_HELP_LEVELXP_help'] = 
    "Specify the experience points required to get through the first level";

$string['SCHEME_SETTING_TEXT_COURSEXP'] = "Course Experience Points";
$string['SCHEME_SETTING_HELP_COURSEXP'] = "the {$string['SCHEME_SETTING_TEXT_COURSEXP']}";
$string['SCHEME_SETTING_HELP_COURSEXP_help'] = 
    "Specify the experience points given each time a course is successfully concluded";

$string['SCHEME_SETTING_WARNING_MESSAGE'] = "";
// DEscribe the behavement of plugin when changing this settings.

$string['SCHEME_SETTING_DEFAULT_INCREMENT'] = "1"; // 0:LINEAL 1:PERCENTUAL
$string['SCHEME_SETTING_DEFAULT_VALUE'] = "1.3";
$string['SCHEME_SETTING_DEFAULT_LEVELXP'] = "1000"; 
$string['SCHEME_SETTING_DEFAULT_COURSEXP'] = "1500";

// ==== FORM MESSAGES =====================================
$string['SETTINGS_EXPERIENCE_DISABLED_REDIRECT'] = "Enable module";
$string['SETTINGS_EXPERIENCE_DISABLED'] =
    "The experience module is deactivated in the *{$string['SETTINGS_GENERAL']}* page 
    if you want to access this configuration you must enable the experiencia module";

$string['maxlength'] = 'Max length allowed is {$a}';
$string['color'] = 'Invalid color value';
$string['integer'] = 'The value must be an integer greater than 0';
$string['float'] =
    'The value must be a float between 1.0 and 2.0 of maximun 5 floating precision 
    points';
