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

class block_gmxp_schemesettingsform extends local_gamedlemaster_form {

    const PLUGIN = 'block_gmxp';
    const INCREMENT = block_gmxp_core::INCREMENT;
    const ELEM_LINEAL = 'linealValue';
    const ELEM_PERCENTUAL = 'percentualValue';
    const VALUE = block_gmxp_core::VALUE;
    const LEVELXP = block_gmxp_core::LEVELXP;
    const COURSEXP = block_gmxp_core::COURSEXP;

    const LINEAL = block_gmxp_core::LINEAL;
    const PERCENTUAL = block_gmxp_core::PERCENTUAL;

    const XP_REGEX = '/^([0-9]){1,9}$/';
    const XP_MAX_REGEX_LENGTH = 9;
    const PERCENTUAL_REGEX = '/^1\.[0-9]{1,5}$/';
    const PERCENTUAL_MAX_REGEX_LENGTH = 7;

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

        $mform->addElement('select', self::INCREMENT,
            get_string('SCHEME_SETTING_TEXT_INCREMENT', self::PLUGIN), array(
            self::LINEAL => get_string('SCHEME_SETTING_TEXT_LINEAL', self::PLUGIN),
            self::PERCENTUAL => get_string('SCHEME_SETTING_TEXT_PERCENTUAL', self::PLUGIN)
        ));

        $mform->addElement('text', self::ELEM_LINEAL,
            get_string('SCHEME_SETTING_TEXT_LINEAL', self::PLUGIN), array(
            'size' => self::XP_MAX_REGEX_LENGTH,
            'maxlength' => self::XP_MAX_REGEX_LENGTH
        ));

        $mform->addElement('text', self::ELEM_PERCENTUAL,
            get_string('SCHEME_SETTING_TEXT_PERCENTUAL', self::PLUGIN), array(
            'size' => self::PERCENTUAL_MAX_REGEX_LENGTH,
            'maxlength' => self::PERCENTUAL_MAX_REGEX_LENGTH
        ));
        
        $mform->addElement('text', self::LEVELXP,
            get_string('SCHEME_SETTING_TEXT_LEVELXP', self::PLUGIN), array(
            'size' => self::XP_MAX_REGEX_LENGTH,
            'maxlength' => self::XP_MAX_REGEX_LENGTH
        ));

        $mform->addElement('text', self::COURSEXP,
            get_string('SCHEME_SETTING_TEXT_COURSEXP', self::PLUGIN), array(
            'size' => self::XP_MAX_REGEX_LENGTH,
            'maxlength' => self::XP_MAX_REGEX_LENGTH
        ));
    }

    private function create_help_messages() {

        $mform = $this->_form;

        $mform->addHelpButton(self::INCREMENT,
            'SCHEME_SETTING_HELP_INCREMENT', self::PLUGIN);

        $mform->addHelpButton(self::ELEM_LINEAL,
            'SCHEME_SETTING_HELP_LINEAL', self::PLUGIN);

        $mform->addHelpButton(self::ELEM_PERCENTUAL,
            'SCHEME_SETTING_HELP_PERCENTUAL', self::PLUGIN);
        
        $mform->addHelpButton(self::LEVELXP,
            'SCHEME_SETTING_HELP_LEVELXP', self::PLUGIN);

        $mform->addHelpButton(self::COURSEXP,
            'SCHEME_SETTING_HELP_COURSEXP', self::PLUGIN);
    }

    private function create_form_restrictions() {

        $mform = $this->_form;

        $key = self::ELEM_PERCENTUAL;
        $mform->setType($key, PARAM_FLOAT);
        // $mform->addRule($key, get_string('required'), 'required', null, 'client');
        // $mform->addRule($key, get_string('float',self::PLUGIN), 'regex',
        //    self::PERCENTUAL_REGEX, 'client');

        $mform->hideIf($key, self::INCREMENT, 'eq', self::LINEAL);

        $key = self::ELEM_LINEAL;
        $errormsg = get_string('integer', self::PLUGIN);
        $mform->setType($key, PARAM_INT);
        // $mform->addRule($key, get_string('required'), 'required', null, 'client');
        // $mform->addRule($key, $errormsg, 'regex', self::XP_REGEX, 'client');
        // $mform->addRule($key, $errormsg, 'nonzero', null, 'client');
        $mform->hideIf($key, self::INCREMENT, 'eq', self::PERCENTUAL);

        $key = self::LEVELXP;
        $mform->setType($key, PARAM_INT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, $errormsg, 'nonzero', null, 'client');
        $mform->addRule($key, $errormsg, 'regex', self::XP_REGEX, 'client');

        $key = self::COURSEXP;
        $mform->setType($key, PARAM_INT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, $errormsg, 'nonzero', null, 'client');
        $mform->addRule($key, $errormsg, 'regex', self::XP_REGEX, 'client');
    }

    private function set_default_values() {

        $mform = $this->_form;

        $key = self::INCREMENT;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $increment = get_config(self::PLUGIN, $key);
        $value = get_config(self::PLUGIN, self::VALUE);

        if ($increment == self::LINEAL) {
            $lineal = $value;
            $percentual = get_string('SCHEME_SETTING_DEFAULT_VALUE', self::PLUGIN);

        } else { // self::PERCENTUAL
            $lineal = 1000;
            $percentual = $value;
        }

        $mform->setDefault(self::ELEM_LINEAL, $lineal);
        $mform->setDefault(self::ELEM_PERCENTUAL, $percentual);

        $key = self::LEVELXP;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = self::COURSEXP;
        $mform->setDefault($key, get_config(self::PLUGIN, $key));
    }

    /**
     * Este método realiza del lado del servidor las mismas validaciones que
     * del lado del cliente, hasta el momento no se requiere una validacion
     * extra del lado del servidor
     */
    public function validation($data, $files) {
        $errors = array();
        /*
        $this->auth_error = false;

        // TODO: Handle error on external page settings/visual_settings.php
        // TODO Pass TO PARENT
        if (!isset($data['sesskey'])) {
            $this->auth_error = true;
            $errors['sesskey'] = "AUTH ERROR";
            return $errors;
        }*/

        $increment = $data[self::INCREMENT];
        $xp_incorrect = get_string('integer', self::PLUGIN);

        if ($increment == self::LINEAL) {
            $key = self::ELEM_LINEAL;
            if( !isset($data[$key]) || empty($data[$key]) ) {
                $errors[$key] = $xp_incorrect;

            } else if (!preg_match( self::XP_REGEX, $data[$key] || $data[$key] == 0)) {
                $errors[$key] = $xp_incorrect;
            }

        } else if ($increment == self::PERCENTUAL) {
            $key = self::ELEM_PERCENTUAL;
            if( !isset($data[$key]) || empty($data[$key]) ) {
                $errors[$key] = get_string('required');

            } else if (!preg_match( self::PERCENTUAL_REGEX, $data[$key]) ||
              $data[$key] <= 1 || $data[$key] > 2 ) {
                $errors[$key] = get_string('float', self::PLUGIN);
            }

        } else {
            $data[self::INCREMENT] = get_string('required');
        }

        $key = self::LEVELXP;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::XP_REGEX, $data[$key]) || $data[$key] == 0)) {
            $errors[$key] = $xp_incorrect;
        }

        $key = self::COURSEXP;
        if( !isset($data[$key]) || empty($data[$key]) ) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::XP_REGEX, $data[$key]) || $data[$key] == 0) {
            $errors[$key] = $xp_incorrect;
        }

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

        if ($keys[self::INCREMENT] == self::LINEAL) {
            $keys[self::VALUE] = $keys[self::ELEM_LINEAL];

        } else { // self::PERCENTUAL
            $keys[self::VALUE] = $keys[self::ELEM_PERCENTUAL];
        }

        unset($keys['submitbutton']);
        unset($keys[self::ELEM_LINEAL]);
        unset($keys[self::ELEM_PERCENTUAL]);
        foreach ($keys as $key => $value) {
            set_config($key, $value, self::PLUGIN);
        }
    }

}

?>
