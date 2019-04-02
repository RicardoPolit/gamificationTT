<?php
/**
 * Created by PhpStorm.
 * User: ricardo
 * Date: 21/03/19
 * Time: 02:01 PM
 */

const ROUTE_GET_PARAM = '_r';

$settings->add(new admin_setting_heading(
    'headerconfigLevels',
    get_string('headerconfigLevels', 'block_simplehtml'),
    get_string('descconfigLevels', 'block_simplehtml')
));

$settings->add(new admin_setting_configselect(
    'simplehtml/typeOfIncrement',
    get_string('typeOfIncrement', 'block_simplehtml'),
    get_string('typeOfIncrementDesc', 'block_simplehtml'),
    'linear',
    array('linear','percentage')
));

$settings->add(new admin_setting_configtext(
    'simplehtml/numValorIncrement',
    get_string('titleIncre', 'block_simplehtml'),
    get_string('descIncre', 'block_simplehtml'),
    1.3,
    PARAM_FLOAT,
    5
));

$settings->add(new admin_setting_heading(
    'headerconfigLevelsFormat',
    get_string('headerconfigLevelsFormat', 'block_simplehtml'),
    get_string('headerconfigLevelsFormatDesc', 'block_simplehtml')
));

$settings->add(new admin_setting_configtext(
    'simplehtml/defaultLevelsMessage',
    get_string('titleDefMessage', 'block_simplehtml'),
    get_string('descDefMessage', 'block_simplehtml'),
    'CONGRATULATIONS',
    PARAM_TEXT,
    20
));

$settings->add(new admin_setting_configtext(
    'simplehtml/defaultLevelsImage',
    get_string('defaultLevelsImage', 'block_simplehtml'),
    get_string('defaultLevelsImageDesc', 'block_simplehtml'),
    '/image/wow.png',
    PARAM_TEXT,
    20
));

/*
$catname = 'block_simplehtml_category';

$settings = new admin_category($catname, 'block_simplehtml');
$settings->visiblename = get_string('generalsettings', 'admin');

$settingspage = new admin_externalpage('block_simplehtml_default_visuals',
    get_string('defaultvisuals', 'block_simplehtml'),
    new moodle_url('/blocks/simplehtml/visualsSimple.php'));
$settings->add($catname, $settingspage);
*/