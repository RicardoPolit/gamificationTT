<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 21/03/19
 * Time: 02:01 PM
 */

$settingsAux = [];

$settingsAux[] = (new admin_setting_heading(
    'block_gmxp/headerconfigLevels',
    get_string('headerconfigLevels', 'block_gmxp'),
    get_string('descconfigLevels', 'block_gmxp')
));

$settingsAux[] = (new admin_setting_configcheckbox(
    'block_gmxp/isActive',
    get_string('checkboxisActive', 'block_gmxp'),
    get_string('checkboxisActivedesc', 'block_gmxp'),
    1));

$settingsAux[] = (new admin_setting_configselect(
    'block_gmxp/typeOfIncrement',
    get_string('typeOfIncrement', 'block_gmxp'),
    get_string('typeOfIncrementDesc', 'block_gmxp'),
    0,
    array(get_string('typeOfIncrementA', 'block_gmxp'),get_string('typeOfIncrementB', 'block_gmxp'))
));

$settingsAux[] = (new admin_setting_configtext(
    'block_gmxp/numValorIncrement',
    get_string('titleIncre', 'block_gmxp'),
    get_string('descIncre', 'block_gmxp'),
    1.3,
    PARAM_FLOAT,
    5
));


$settingsAux[] = (new admin_setting_configtext(
    'block_gmxp/firstExpRequiried',
    get_string('titleFirstExpRequired', 'block_gmxp'),
    get_string('descFirstExpRequired', 'block_gmxp'),
    1000,
    PARAM_INT,
    10
));


$settingsAux[] = (new admin_setting_configtext(
    'block_gmxp/firstExpGiven',
    get_string('titleExpGiven', 'block_gmxp'),
    get_string('descExpGiven', 'block_gmxp'),
    1500,
    PARAM_INT,
    10
));


$settingsAux[] = (new admin_setting_heading(
    'block_gmxp/headerconfigLevelsFormat',
    get_string('headerconfigLevelsFormat', 'block_gmxp'),
    get_string('headerconfigLevelsFormatDesc', 'block_gmxp')
));

$settingsAux[] = (new admin_setting_configtext(
    'block_gmxp/defaultLevelsName',
    get_string('titleDefName', 'block_gmxp'),
    get_string('descDefName', 'block_gmxp'),
    get_string('defaultDefName','block_gmxp'),
    PARAM_TEXT,
    60
));

$settingsAux[] = (new admin_setting_configtext(
    'block_gmxp/defaultLevelsMessage',
    get_string('titleDefMessage', 'block_gmxp'),
    get_string('descDefMessage', 'block_gmxp'),
    get_string('defaultDefMessage','block_gmxp'),
    PARAM_RAW,
    60
));


$settingsAux[] = (new admin_setting_configtextarea(
    'block_gmxp/defaultLevelsDescription',
    get_string('titleDefDescription', 'block_gmxp'),
    get_string('descDefDescription', 'block_gmxp'),
    get_string('defaultDefDescription','block_gmxp'),
    PARAM_RAW,
    5,
    5
));

$settingsAux[] = (new admin_setting_configcolourpicker(
    'block_gmxp/defaultColorPickerLevels',
    get_string('defColorPick', 'block_gmxp'),
    get_string('defColorPickdesc','block_gmxp'),
    '#0B619F',
    null,
    true
));

$settingsAux[] = (new admin_setting_configcolourpicker(
    'block_gmxp/defaultColorPickerProgressBar',
    get_string('defColorPickProgressBar', 'block_gmxp'),
    get_string('defColorPickdesc','block_gmxp'),
    '#0B619F',
    null,
    true
));

$settingsAux[] = (new admin_setting_configstoredfile(
    'block_gmxp/imageDefecto',
    new lang_string('defaultLevelsImage', 'block_gmxp'),
    new lang_string('defaultLevelsImageDesc', 'block_gmxp'),
    'level_images_simplehtml',
    0,
    ['subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => '.png']
));

$settings = new admin_category('block_gmxp_category', 'Gamedle Level');

$settingsurl = new moodle_url('/blocks/gmxp/visualsSettingsLevels.php');
$external = new admin_externalpage('block_gmxp_ext', get_string('nameVisualSettings','block_gmxp'), $settingsurl);

$settingspage = new admin_settingpage('block_gmxp_default_settings', get_string('nameGeneralSettings','block_gmxp'));

array_map(function($setting) use ($settingspage) {
    $settingspage->add($setting);
}, $settingsAux);

$settings->add('block_gmxp_category', $settingspage);

$settings->add('block_gmxp_category',$external);

