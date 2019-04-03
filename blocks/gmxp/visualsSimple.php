<?php

/*
 *
 * Esto todavia no sirve :v está en proceso.
 *
 */

namespace block_gmxp;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

use moodleform;
admin_externalpage_setup('visualsSimple');

class visualsSimple extends moodleform {

    public function definition() {
        $mform = $this->_form;
        $mform->addElement('filemanager', 'badges', get_string('levelbadges', 'block_gmxp'), null, $this->_customdata['fmoptions']);
        $mform->addElement('static', '', '', get_string('levelbadgesformhelp', 'block_gmxp'));
        $this->add_action_buttons();
    }
}
