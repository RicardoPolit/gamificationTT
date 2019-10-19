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

define('PLUGIN', 'block_gmxp');

/**
 * 
 * Docs: https://docs.moodle.org/dev/lib/formslib.php_Form_Definition
 */
class block_gmxp_visualsettingsform extends moodleform {

    protected function definition() {

        $mform = $this->_form; // Inherited from moodle form
        $mform->setDisableShortforms(true); // TODO CHECK WHY?

        $this->create_definition();
        $this->create_help_messages();
        $this->create_form_restrictions();
        $this->set_default_values();
        $this->add_action_buttons();
    }

    private function create_definition() {

        $mform = $this->_form;

        $mform->addElement('text',
            get_string('SYS_SETTINGS_VISUAL_TITLE', PLUGIN),
            get_string('VISUAL_SETTING_TEXT_TITLE', PLUGIN));
    }

    private function create_help_messages() {

        $mform = $this->_form;
    }

    private function create_form_restrictions() {

        $mform = $this->_form;

        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', PLUGIN);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('maxlength', PLUGIN, 40), 'maxlength', null, 'client');

        local_gamedlemaster_log::info(
            get_string('maxlength', PLUGIN, '40')
        );
    }

    private function set_default_values() {

        $mform = $this->_form;

        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', PLUGIN);
        $mform->setDefault($key, get_config(PLUGIN, $key));
    }
}

?>
