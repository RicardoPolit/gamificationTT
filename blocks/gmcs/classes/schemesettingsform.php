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
class block_gmcs_schemesettingsform extends local_gamedlemaster_form {

    const PLUGIN = 'block_gmcs';
    const SILVER_TO_GOLD = block_gmcs_core::SILVER_TO_GOLD;
    const DEFEAT_SYSTEM  = block_gmcs_core::DEFEAT_SYSTEM;
    const DEFEAT_USER    = block_gmcs_core::DEFEAT_USER;
    const ANSWER_QUESTION    = block_gmcs_core::ANSWER_QUESTION;

    const CURRENCY_MAX_REGEX_LENGTH = 10;
    const CURRENCY_REGEX = '/^[1-9][0-9]*$/';

    protected function definition() {

        $mform = $this->_form; // Inherited from moodle form

        $this->set_title(get_string('SETTINGS_SCHEME', self::PLUGIN));
        $this->set_subtitle(get_string('SETTINGS_SCHEME_HEADER', self::PLUGIN));
        $this->set_description(get_string('SETTINGS_SCHEME_DESC', self::PLUGIN));

        $this->create_definition();
        $this->create_help_messages();
        $this->create_form_restrictions();

        $this->set_default_values();
        $this->add_action_buttons();
    }

    private function create_definition() {

        $mform = $this->_form;

        $mform->addElement('text', self::SILVER_TO_GOLD,
            get_string('SCHEME_SETTING_TEXT_SILVER', self::PLUGIN), array(
            'size' => self::CURRENCY_MAX_REGEX_LENGTH,
            'maxlength' => self::CURRENCY_MAX_REGEX_LENGTH
        ));

        $mform->addElement('text', self::DEFEAT_USER,
            get_string('SCHEME_SETTING_TEXT_WIN_USER', self::PLUGIN), array(
            'size' => self::CURRENCY_MAX_REGEX_LENGTH,
            'maxlength' => self::CURRENCY_MAX_REGEX_LENGTH
        ));

        $mform->addElement('text', self::DEFEAT_SYSTEM,
            get_string('SCHEME_SETTING_TEXT_WIN_SYSTEM', self::PLUGIN), array(
            'size' => self::CURRENCY_MAX_REGEX_LENGTH,
            'maxlength' => self::CURRENCY_MAX_REGEX_LENGTH
        ));

        $mform->addElement('text', self::ANSWER_QUESTION,
            get_string('SCHEME_SETTING_TEXT_QUESTION', self::PLUGIN), array(
            'size' => self::CURRENCY_MAX_REGEX_LENGTH,
            'maxlength' => self::CURRENCY_MAX_REGEX_LENGTH
        ));
    }

    private function create_help_messages() {
        $mform = $this->_form;

        $mform->addHelpButton(self::SILVER_TO_GOLD,
            'SCHEME_SETTING_HELP_SILVER', self::PLUGIN);

        $mform->addHelpButton(self::DEFEAT_SYSTEM,
            'SCHEME_SETTING_HELP_WIN_SYSTEM', self::PLUGIN);

        $mform->addHelpButton(self::DEFEAT_USER,
            'SCHEME_SETTING_HELP_WIN_USER', self::PLUGIN);

        $mform->addHelpButton(self::ANSWER_QUESTION,
            'SCHEME_SETTING_HELP_QUESTION', self::PLUGIN);
    }

    private function create_form_restrictions() {
        $mform = $this->_form;

        $key = self::SILVER_TO_GOLD;
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('currency', self::PLUGIN),
            'regex', self::CURRENCY_REGEX, 'client');

        $key = self::DEFEAT_SYSTEM;
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('currency', self::PLUGIN),
            'regex', self::CURRENCY_REGEX, 'client');

        $key = self::DEFEAT_USER;
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('currency', self::PLUGIN),
            'regex', self::CURRENCY_REGEX, 'client');

        $key = self::ANSWER_QUESTION;
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('currency', self::PLUGIN),
            'regex', self::CURRENCY_REGEX, 'client');
    }

    private function set_default_values() {
        $mform = $this->_form;

        $key = self::SILVER_TO_GOLD;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = self::DEFEAT_SYSTEM;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = self::DEFEAT_USER;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = self::ANSWER_QUESTION;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));
    }

    /**
     * Este método realiza del lado del servidor las mismas validaciones que
     * del lado del cliente, hasta el momento no se requiere una validacion
     * extra del lado del servidor
     */
    public function validation($data, $files) {
        $errors = array();

        $key = self::SILVER_TO_GOLD;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::CURRENCY_REGEX, $data[$key]) ||
          $data[$key] <= 0) {
            $errors[$key] = get_string('currency', self::PLUGIN);
        }

        $key = self::DEFEAT_SYSTEM;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::CURRENCY_REGEX, $data[$key]) ||
          $data[$key] <= 0) {
            $errors[$key] = get_string('currency', self::PLUGIN);
        }

        $key = self::DEFEAT_USER;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::CURRENCY_REGEX, $data[$key]) ||
          $data[$key] <= 0) {
            $errors[$key] = get_string('currency', self::PLUGIN);
        }

        $key = self::ANSWER_QUESTION;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::CURRENCY_REGEX, $data[$key]) ||
          $data[$key] <= 0) {
            $errors[$key] = get_string('currency', self::PLUGIN);
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

        foreach ($keys as $key => $value) {
            set_config($key, $value, self::PLUGIN);
        }
    }

}

?>
