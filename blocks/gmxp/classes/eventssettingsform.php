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

class block_gmxp_eventssettingsform extends local_gamedlemaster_form {

    const PLUGIN = 'block_gmxp';
    const COMPETENCE = block_gmxp_core::COMPETENCE;
    const COMPETENCEXP = block_gmxp_core::COMPETENCEXP;

    const XP_REGEX = '/^([0-9]){1,9}$/';
    const XP_MAX_REGEX_LENGTH = 9; 

    protected function definition() {

        $mform = $this->_form; // Inherited from moodle form

        $this->set_title(get_string('SETTINGS_EVENTS', self::PLUGIN));
        $this->set_subtitle(get_string('SETTINGS_EVENTS_HEADER', self::PLUGIN));
        $this->set_description(get_string('SETTINGS_EVENTS_DESC', self::PLUGIN));

        $this->create_definition();
        $this->create_help_messages();
        $this->create_form_restrictions();

        $this->set_default_values();
        $this->add_action_buttons();
    }

    private function create_definition() {

        $mform = $this->_form;
        
        $mform->addElement('advcheckbox', self::COMPETENCE,
            get_string('EVENTS_SETTING_TEXT_COMP', self::PLUGIN),
            get_string('EVENTS_SETTING_TEXT_XP_SUPPORT', self::PLUGIN),
            array('group' => 1), array(0, 1));

        $mform->addElement('text', self::COMPETENCEXP,
            get_string('EVENTS_SETTING_TEXT_XP', self::PLUGIN), array(
            'size' => self::XP_MAX_REGEX_LENGTH,
            'maxlength' => self::XP_MAX_REGEX_LENGTH
        ));
    }

    private function create_help_messages() {

        $mform = $this->_form;

        $mform->addHelpButton(self::COMPETENCE,
            'EVENTS_SETTING_HELP_COMP', self::PLUGIN);

        $mform->addHelpButton(self::COMPETENCEXP,
            'EVENTS_SETTING_HELP_XP', self::PLUGIN);
    }

    private function create_form_restrictions() {

        $mform = $this->_form;
        $errormsg = get_string('integer', self::PLUGIN);

        $key = self::COMPETENCEXP;
        $mform->setType($key, PARAM_INT);
        // $mform->addRule($key, $errormsg, 'nonzero', null, 'client');
        // $mform->addRule($key, $errormsg, 'regex', self::XP_REGEX, 'client');
        $mform->disabledIf($key, self::COMPETENCE, 'notchecked');
    }

    private function set_default_values() {

        $mform = $this->_form;

        $key = self::COMPETENCE;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = self::COMPETENCEXP;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));
    }

    /**
     * Este método realiza del lado del servidor las mismas validaciones que
     * del lado del cliente, hasta el momento no se requiere una validacion
     * extra del lado del servidor
     */
    public function validation($data, $files) {
        $errors = array();
        $xp_incorrect = get_string('integer', self::PLUGIN);

        // TODO Validate self::COMPETENCE & parent::VALIDATE(SESSKEY)i

        if ($data[self::COMPETENCE]) {

            $key = self::COMPETENCEXP;
            if ( !isset($data[$key]) ) {
                $errors[$key] = get_config('required');

            } else if (
            !preg_match( self::XP_REGEX, $data[$key]) || $data[$key] <= 0 ) {
                $errors[$key] = $xp_incorrect;
            }
        }

        // TODO PASS TO PARENT
        $this->has_error = false;
        if (!empty($errors)) {
            $this->has_error = true;
        }
        return $errors;
    }

    /**
     * Este método actualiza las configuraciones de la
     * base de datos con los valores enviados en el formulacion
     * 
     * El objeto (stdClass) de entrada se convierte en un arreglo
     * donde cada clave es el identificador de la configuracion
     * del plugin y el valor de dicha clave es el nuevo valor de
     * la configuracion
     */
    public function submit_changes(stdClass $changes) {

        $keys = get_object_vars($changes);
        unset($keys['submitbutton']);

        if($keys[self::COMPETENCE] == 0) {
            unset($keys[self::COMPETENCEXP]);
        }

        foreach ($keys as $key => $value) {
            set_config($key, $value, self::PLUGIN);
        }
    }

}

?>
