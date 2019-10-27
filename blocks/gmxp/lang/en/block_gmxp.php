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
$string['gmxp'] = 'Gamedle Level';
$string['gmxp:addinstance']   = 'Add a new Gamedle level block';
$string['gmxp:myaddinstance'] = 'Add a new Gamedle level block to the My Moodle page';

/**
 * Global variables in the system, should not be translated.
 */
$string['SYS_SETTINGS_CATEGORY'] = 'block_gmxp_category';

$string['SYS_SETTINGS_GENERAL'] = "block_gmxp_general_settings";
$string['SYS_SETTINGS_GENERAL_ACTIVATED'] = "activated";
$string['SYS_SETTINGS_GENERAL_EVENTS'] = "events";

$string['SYS_SETTINGS_VISUAL'] = "block_gmxp/visual_settings";
$string['SYS_SETTINGS_VISUAL_TITLE'] = "title";
$string['SYS_SETTINGS_VISUAL_DESCRIPTION'] = "description";
$string['SYS_SETTINGS_VISUAL_MESSAGE'] = "message";
$string['SYS_SETTINGS_VISUAL_COLORLVL'] = "colorLvl";
$string['SYS_SETTINGS_VISUAL_COLORBAR'] = "colorBar";
$string['SYS_SETTINGS_VISUAL_IMAGE'] = "image";
$string['SYS_SETTINGS_VISUAL_IMAGE_PATH'] = "/blocks/gmxp/pix/";

$string['SYS_SETTINGS_SCHEME'] = "block_gmxp/scheme_settings";
$string['SYS_SETTINGS_SCHEME_INCREMENT'] = "increment";
$string['SYS_SETTINGS_SCHEME_VALUE'] = "incrementValue";
$string['SYS_SETTINGS_SCHEME_LEVELXP'] = "levelXP";
$string['SYS_SETTINGS_SCHEME_COURSEXP'] = "courseXP";
$string['SYS_SETTINGS_EVENTS'] = "block_gmxp/events_settings";

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









$string['valueDownIntLevels'] = '1';
$string['valueUpIntLevels'] = '10000';
$string['valueExpGivenDown'] = '1';
$string['valueExpGivenUp'] = '100000';
$string['valueExpRequiredFirstLevelDown'] = '1';
$string['valueExpRequiredFirstLevelUp'] = '10000';
$string['valueDownFloatLevels'] = '1.1';
$string['valueUpFloatLevels'] = '2.0';
$string['valueNumIncrementDefault'] = $string['valueDownFloatLevels'].' or '.$string['valueDownIntLevels'];
$string['valueDownStringNameLevels'] = '1';
$string['valueUpStringNameLevels'] = '20';
$string['valueDownStringMessageLevels'] = '1';
$string['valueUpStringMessageLevels'] = '50';
$string['valueDownStringDescLevels'] = '1';
$string['valueUpStringDescLevels'] = '150';


$string['nameGeneralSettings'] = 'General Settings';
$string['nameVisualSettingsSections'] = 'Level view sections settings';
$string['nameVisualSettingsGeneral'] = 'Level view general settings';

/* ADMIN FORM STRINGS */
$string['headerconfigLevels'] = 'Level experience settings';
$string['descconfigLevels'] = 'Set the increment to go up in the levels';
$string['typeOfIncrement'] = 'Type of increment';
$string['typeOfIncrementA'] = 'Percent';
$string['typeOfIncrementB'] = 'Linear';
$string['typeOfIncrementDesc'] = 'Select the type of increment';
$string['titleIncre'] = 'Increment value of levels';
$string['descIncre'] = 'Set the increment value of the levels, if you set the type percentage the value has to be on range ['.$string['valueDownFloatLevels'].' - '.$string['valueUpFloatLevels'].']';
$string['descIncre_help'] = 'If you set the type percentage the value has to be on range ['.$string['valueDownFloatLevels'].' - '.$string['valueUpFloatLevels'].'] and if the type is linear the value range is ['.$string['valueDownIntLevels'].' - '.$string['valueUpIntLevels'].']';
$string['headerconfigLevelsFormat'] = 'Format of levels settings';
$string['headerconfigLevelsFormatDesc'] = 'Here you have the option of having one default image and message for all the levels or customize blocks of levels';
$string['titleDefMessage'] = 'Default congratulations message';
$string['descDefMessage'] = 'Write your own congratulations message for all the levels';
$string['descDefMessage_help'] = 'Write your own congratulations message for all the levels with a max character of '.$string['valueUpStringMessageLevels'];
$string['defaultDefMessage'] = 'Congratulations';
$string['defaultLevelsImage'] = 'Default levels image';
$string['defaultLevelsImageDesc'] = 'Upload the default image for the levels, the name of the file has to be (defaultLevel.png)';
$string['defaultLevelsImageDesc_help'] = 'Upload the default image for the levels, the name of the file has to be (defaultLevel.png or defaultLevel.jpg)';
$string['titleDefName'] = 'Default levels name';
$string['descDefName'] = 'Set the default name for all the levels';
$string['descDefName_help'] = 'Set the default name for all the levels with a max character of '.$string['valueUpStringNameLevels'];
$string['defaultDefName'] = 'Casual Levels';
$string['checkboxisActive'] = 'Active plugin';
$string['checkboxisActivedesc'] = 'Is the plugin active?';
$string['defColorPick'] = 'Level number color';
$string['defColorPickProgressBar'] = 'Progress Bar color';
$string['defColorPickdescProgressBar'] = 'Choose the default progress bar color';
$string['defColorPickdescProgressBar_help'] = 'Choose the default progress bar color, the value has to be in hexadecimal and a validate value from [#000000 - #FFFFFF]';
$string['defColorPickdesc'] = 'Choose the default levels color';
$string['defColorPickdesc_help'] = 'Choose the default progress bar color, the value has to be in hexadecimal and a validate value from [#000000 - #FFFFFF]';
$string['titleFirstExpRequired'] = 'First level exp.';
$string['descFirstExpRequired'] = 'Experience point required to complete the first level';
$string['descFirstExpRequired_help'] = 'Experience point required to complete the first level the value has to be on range ['.$string['valueExpRequiredFirstLevelDown'].' - '.$string['valueExpRequiredFirstLevelUp'].']';


$string['titleExpGiven'] = 'Exp. per course';
$string['descExpGiven'] = 'How many experience points a course has that can be divided in each of its sections';
$string['descExpGiven_help'] = 'How many experience points a course has that can be divided in each of its sections the value has to be on range ['.$string['valueExpGivenDown'].' - '.$string['valueExpGivenUp'].']';

$string['titleDefDescription'] = 'Levels description';
$string['descDefDescription'] = 'Add a level description to motivate the user to get more experience';
$string['descDefDescription_help'] = 'Add a level description to motivate the user to get more experience with a max character of '.$string['valueUpStringDescLevels'];
$string['defaultDefDescription'] = 'These are awesome levels!';

/* Visuals settings levels */

$string['nameSecc'] = 'Definition of sections of levels';
$string['descGeneral'] = 'Description of page';
$string['descDescSections'] = 'Here you can define sections of levels to set different visuals on each section';
$string['sectionscount'] = 'Number of sections';
$string['createSections'] = 'Create sections and define them';
$string['sectionx'] = 'Section #{$a}';
$string['levelstart'] = 'Start level of the section';
$string['levelend'] = 'End level of the section';
$string['errorsectionsincorrect'] = 'The number of sections cannot be less than 2';

/* Notification messages */

$string['errorTopMessage'] = 'Error in one or many fields';
$string['successTopMessage'] = 'Changes saved';

/* Error messages */

$string['errorValorIncrement1'] = 'Change the default value';
$string['errorValorIncrement2'] = 'The value has to be a fraction with only one decimal';
$string['errorValorIncrement3'] = 'The value has to be between ['.$string['valueDownFloatLevels'].' - '.$string['valueUpFloatLevels'].']';
$string['errorValorIncrement4'] = 'The value has to be an integer';
$string['errorValorIncrement5'] = 'The value has to be between ['.$string['valueDownIntLevels'].' - '.$string['valueUpIntLevels'].']';

$string['errorFirstLevelExp1'] = 'The value has to be an integer';
$string['errorFirstLevelExp2'] = 'The value has to be between ['.$string['valueExpRequiredFirstLevelDown'].' - '.$string['valueExpRequiredFirstLevelUp'].']';
$string['errorExpCourse3'] = 'The value has to be smaller than the value of '.$string['titleExpGiven'];

$string['errorExpCourse1'] = 'The value has to be an integer';
$string['errorExpCourse2'] = 'The value has to be between ['.$string['valueExpGivenDown'].' - '.$string['valueExpGivenUp'].']';
$string['errorExpCourse3'] = 'The value has to be bigger than the value of '.$string['titleFirstExpRequired'];

$string['errorLevelsName1'] = 'To many characters';

$string['errorLevelsMessage1'] = 'To many characters';

$string['errorLevelsDesc1'] = 'To many characters';

$string['errorColor'] = 'Color not valid, this is not a valid hexadecimal color representation';

