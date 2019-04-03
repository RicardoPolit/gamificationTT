<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 21/03/19
 * Time: 02:01 PM
 */

const ROUTE_GET_PARAM = '_r';

$settings->add(new admin_setting_heading(
    'block_simplehtml/headerconfigLevels',
    get_string('headerconfigLevels', 'block_simplehtml'),
    get_string('descconfigLevels', 'block_simplehtml')
));

$settings->add(new admin_setting_configselect(
    'block_simplehtml/typeOfIncrement',
    get_string('typeOfIncrement', 'block_simplehtml'),
    get_string('typeOfIncrementDesc', 'block_simplehtml'),
    'linear',
    array('linear','percentage')
));

$settings->add(new admin_setting_configtext(
    'block_simplehtml/numValorIncrement',
    get_string('titleIncre', 'block_simplehtml'),
    get_string('descIncre', 'block_simplehtml'),
    1.3,
    PARAM_FLOAT,
    5
));

$settings->add(new admin_setting_heading(
    'block_simplehtml/headerconfigLevelsFormat',
    get_string('headerconfigLevelsFormat', 'block_simplehtml'),
    get_string('headerconfigLevelsFormatDesc', 'block_simplehtml')
));

$settings->add(new admin_setting_configtext(
    'block_simplehtml/defaultLevelsMessage',
    get_string('titleDefMessage', 'block_simplehtml'),
    get_string('descDefMessage', 'block_simplehtml'),
    'CONGRATULATIONS',
    PARAM_TEXT,
    50
));

$settings->add(new admin_setting_configstoredfile(
    'block_simplehtml/imageDefecto',
    new lang_string('defaultLevelsImage', 'block_simplehtml'),
    new lang_string('defaultLevelsImageDesc', 'block_simplehtml'),
    'level_images_simplehtml',
    0,
    ['subdirs' => 0, 'maxfiles' => 1, 'accepted_types' => '.png']
));
