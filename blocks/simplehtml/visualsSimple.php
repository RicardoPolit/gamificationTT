<?php

/*
 *
 * Esto todavia no sirve :v está en proceso.
 *
 */

echo 'Esto todavia no sirve :v está en proceso.';

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

use moodleform;

class visualsSimple extends moodleform {

    public function definition() {
        $mform = $this->_form;
        $mform->addElement('filemanager', 'badges', get_string('levelbadges', 'block_simplehtml'), null, $this->_customdata['fmoptions']);
        $mform->addElement('static', '', '', get_string('levelbadgesformhelp', 'block_simplehtml'));
        $this->add_action_buttons();
    }

}
