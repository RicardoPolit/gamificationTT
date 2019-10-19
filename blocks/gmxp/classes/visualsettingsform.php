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



/**
 * 
 * Docs: https://docs.moodle.org/dev/lib/formslib.php_Form_Definition
 */
class block_gmxp_visualsettingsform extends moodleform {

    const PLUGIN = 'block_gmxp';
    protected function definition() {

        $mform = $this->_form; // Inherited from moodle form
        // $mform->setDisableShortforms(true); // TODO CHECK WHY?

        $this->create_definition();
        $this->create_help_messages();
        $this->create_form_restrictions();
        $this->set_default_values();

        $this->add_action_buttons();
    }

    private function create_definition() {

        $mform = $this->_form;

        $mform->addElement('text',
            get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_TITLE', self::PLUGIN));
    }

    private function create_help_messages() {

        $mform = $this->_form;
    }

    private function create_form_restrictions() {

        $mform = $this->_form;

        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('maxlength', self::PLUGIN, 30),
            'maxlength', 30, 'client');
    }

    private function set_default_values() {

        $mform = $this->_form;

        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));
    }

    public function validation($data, $files) {
        return array();
    }

    public function submitChanges($changes) {
        $keys = get_object_vars($changes);
        unset($keys['submitbutton']);

        foreach ($keys as $key => $value) {
            local_gamedlemaster_log::info(
            "set_config('{$key}', {$value}, {self::PLUGIN});",'SUBMIT');
        }
    }
}

?>
