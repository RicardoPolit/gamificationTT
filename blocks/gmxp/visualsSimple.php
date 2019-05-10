<?php

require(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
admin_externalpage_setup('block_gmxp_ext');


// functionality like processing form submissions goes here

class visualsSimple extends moodleform {

    protected function definition() {
        $mform = $this->_form;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('titleDefName', 'block_gmxp'));

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_text',get_string('descDefName', 'block_gmxp'));
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_RAW);

        $this->add_action_buttons();
    }
}

$output = (new visualsSimple())->render();

echo $OUTPUT->header();
echo $output;
echo $OUTPUT->footer();