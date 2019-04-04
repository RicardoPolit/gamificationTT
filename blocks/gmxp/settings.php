<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 21/03/19
 * Time: 02:01 PM
 */

$settings->add(new admin_setting_heading(
    'block_gmxp/headerconfigLevels',
    get_string('headerconfigLevels', 'block_gmxp'),
    get_string('descconfigLevels', 'block_gmxp')
));

$settings->add(new admin_setting_configcheckbox(
    'block_gmxp/isActive',
    get_string('checkboxisActive', 'block_gmxp'),
    get_string('checkboxisActivedesc', 'block_gmxp'),
    1));

$settings->add(new admin_setting_configselect(
    'block_gmxp/typeOfIncrement',
    get_string('typeOfIncrement', 'block_gmxp'),
    get_string('typeOfIncrementDesc', 'block_gmxp'),
    'percentage',
    array('linear','percentage')
));

$settings->add(new admin_setting_configtext(
    'block_gmxp/numValorIncrement',
    get_string('titleIncre', 'block_gmxp'),
    get_string('descIncre', 'block_gmxp'),
    1.3,
    PARAM_FLOAT,
    5
));

$settings->add(new admin_setting_heading(
    'block_gmxp/headerconfigLevelsFormat',
    get_string('headerconfigLevelsFormat', 'block_gmxp'),
    get_string('headerconfigLevelsFormatDesc', 'block_gmxp')
));

$settings->add(new admin_setting_configtext(
    'block_gmxp/defaultLevelsName',
    get_string('titleDefName', 'block_gmxp'),
    get_string('descDefName', 'block_gmxp'),
    'Casual Levels',
    PARAM_TEXT,
    20
));

$settings->add(new admin_setting_configtext(
    'block_gmxp/defaultLevelsMessage',
    get_string('titleDefMessage', 'block_gmxp'),
    get_string('descDefMessage', 'block_gmxp'),
    'CONGRATULATIONS',
    PARAM_TEXT,
    50
));

$settings->add(new admin_setting_configcolourpicker(
    'block_gmxp/defaultColorPickerLevels',
    get_string('defColorPick', 'block_gmxp'),
    get_string('defColorPickdesc','block_gmxp'),
    '#0B619F',
    null,
    true
));

$settings->add(new admin_setting_configstoredfile(
    'block_gmxp/imageDefecto',
    new lang_string('defaultLevelsImage', 'block_gmxp'),
    new lang_string('defaultLevelsImageDesc', 'block_gmxp'),
    'level_images_simplehtml',
    0,
    ['subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => '.png']
));
