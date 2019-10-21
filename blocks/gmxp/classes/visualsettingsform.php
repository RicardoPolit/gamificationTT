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


MoodleQuickForm::registerElementType('customcert_colourpicker',
    $CFG->dirroot . '/blocks/gmxp/includes/colourpicker.php',
    'MoodleQuickForm_customcert_colourpicker');

/**
 * 
 * Docs: https://docs.moodle.org/dev/lib/formslib.php_Form_Definition
 */
class block_gmxp_visualsettingsform extends moodleform {

    const PLUGIN = 'block_gmxp';
    const COLOR_REGEX = '/^#[A-Fa-f0-9]{6,6}$/';
    const IMAGE_FILE_TYPES = array(".png", ".jpg");
    const RESERVED_IMAGE_NAME1 = "icon.png";
    const RESERVED_IMAGE_NAME2 = "icon.svg";

    const TITLE_MAX_LENGTH = 40;
    const MSG_MAX_LENGTH   = 30;
    const DESC_MAX_LENGTH  = 200;
    const DESC_COLS        = 40;

    public $file_error = false;
    public $auth_error = false;
    public $error = '';

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
            get_string('VISUAL_SETTING_TEXT_TITLE', self::PLUGIN),
            array(
                'size' => self::TITLE_MAX_LENGTH,
                'maxlength' => self::TITLE_MAX_LENGTH
            )
        );

        $mform->addElement('textarea',
            get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_DESCRIPTION', self::PLUGIN),
            array(
                'maxlength' => self::DESC_MAX_LENGTH,
                'cols' => self::DESC_COLS,
            )
        );

        $mform->addElement('text',
            get_string('SYS_SETTINGS_VISUAL_MESSAGE', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_MESSAGE', self::PLUGIN),
            array(
                'size' => self::MSG_MAX_LENGTH,
                'maxlength' => self::MSG_MAX_LENGTH
            )
        );

        $mform->addElement('customcert_colourpicker',
            get_string('SYS_SETTINGS_VISUAL_COLORLVL', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_COLORLVL', self::PLUGIN));

        $mform->addElement('customcert_colourpicker',
            get_string('SYS_SETTINGS_VISUAL_COLORBAR', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_COLORBAR', self::PLUGIN));

        $mform->addElement('filepicker',
            get_string('SYS_SETTINGS_VISUAL_IMAGE', self::PLUGIN),
            get_string('VISUAL_SETTING_TEXT_IMAGE', self::PLUGIN),
            null, // TODO Why null?
            array( 'accepted_types' => self::IMAGE_FILE_TYPES ));
    }

    private function create_help_messages() {

        $mform = $this->_form;

        $mform->addHelpButton(
            get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN),
            'VISUAL_SETTING_HELP_TITLE', self::PLUGIN);
        
        $mform->addHelpButton(
            get_string('SYS_SETTINGS_VISUAL_MESSAGE', self::PLUGIN),
            'VISUAL_SETTING_HELP_MESSAGE', self::PLUGIN);

        $mform->addHelpButton(
            get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', self::PLUGIN),
            'VISUAL_SETTING_HELP_DESCRIPTION', self::PLUGIN);

        $mform->addHelpButton(
            get_string('SYS_SETTINGS_VISUAL_COLORLVL', self::PLUGIN),
            'VISUAL_SETTING_HELP_COLORLVL', self::PLUGIN);

        $mform->addHelpButton(
            get_string('SYS_SETTINGS_VISUAL_COLORBAR', self::PLUGIN),
            'VISUAL_SETTING_HELP_COLORBAR', self::PLUGIN);

        $mform->addHelpButton(
            get_string('SYS_SETTINGS_VISUAL_IMAGE', self::PLUGIN),
            'VISUAL_SETTING_HELP_IMAGE', self::PLUGIN);
    }

    private function create_form_restrictions() {

        $mform = $this->_form;

        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('maxlength', self::PLUGIN,
            self::TITLE_MAX_LENGTH), 'maxlength', self::TITLE_MAX_LENGTH, 'client');

        $key = get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('maxlength', self::PLUGIN,
            self::DESC_MAX_LENGTH), 'maxlength', self::DESC_MAX_LENGTH, 'client');

        $key = get_string('SYS_SETTINGS_VISUAL_MESSAGE', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, get_string('maxlength', self::PLUGIN,
            self::MSG_MAX_LENGTH), 'maxlength', self::MSG_MAX_LENGTH, 'client');

        // Message of error in color field
        $colorErr = get_string('color', self::PLUGIN);

        $key = get_string('SYS_SETTINGS_VISUAL_COLORLVL', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, $colorErr, 'regex', self::COLOR_REGEX, 'client');

        $key = get_string('SYS_SETTINGS_VISUAL_COLORBAR', self::PLUGIN);
        $mform->setType($key, PARAM_TEXT);
        $mform->addRule($key, get_string('required'), 'required', null, 'client');
        $mform->addRule($key, $colorErr, 'regex', self::COLOR_REGEX, 'client');

        $key = get_string('SYS_SETTINGS_VISUAL_IMAGE', self::PLUGIN);
        // $mform->setType($key, PARAM_TEXT);
    }

    private function set_default_values() {

        $mform = $this->_form;

        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = get_string('SYS_SETTINGS_VISUAL_MESSAGE', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = get_string('SYS_SETTINGS_VISUAL_COLORLVL', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));

        $key = get_string('SYS_SETTINGS_VISUAL_COLORBAR', self::PLUGIN);
        $mform->setDefault($key, get_config(self::PLUGIN, $key));
    }

    /**
     * Este método realiza del lado del servidor las mismas validaciones que
     * del lado del cliente, hasta el momento no se requiere una validacion
     * extra del lado del servidor
     */
    public function validation($data, $files) {

        $errors = array();
        $this->auth_error = false;

        // TODO: Handle error on external page settings/visual_settings.php
        if (!isset($data['sesskey'])) {
            $this->auth_error = true;
            $errors['sesskey'] = "AUTH ERROR";
            return $errors;
        }

        $key = get_string('SYS_SETTINGS_VISUAL_TITLE', self::PLUGIN);
        if ($data[$key] == null) {
            $errors[$key] = get_string('required');

        } else if (strlen($data[$key]) >= self::TITLE_MAX_LENGTH) {
            $errors[$key] =
              get_string('maxlength', self::PLUGIN, self::TITLE_MAX_LENGTH);
        }


        $key = get_string('SYS_SETTINGS_VISUAL_DESCRIPTION', self::PLUGIN);
        if ($data[$key] == null) {
            $errors[$key] = get_string('required');

        } else if (strlen($data[$key]) >= self::DESC_MAX_LENGTH) {
            $errors[$key] =
              get_string('maxlength', self::PLUGIN, self::DESC_MAX_LENGTH);
        }


        $key = get_string('SYS_SETTINGS_VISUAL_MESSAGE', self::PLUGIN);
        if ($data[$key] == null) {
            $errors[$key] = get_string('required');

        } else if (strlen($data[$key]) >= self::MSG_MAX_LENGTH) {
            $errors[$key] =
              get_string('maxlength', self::PLUGIN, self::MSG_MAX_LENGTH);
        }


        $key = get_string('SYS_SETTINGS_VISUAL_COLORLVL', self::PLUGIN);
        if ($data[$key] == null) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::COLOR_REGEX, $data[$key])) {
            $errors[$key] = get_string('color', self::PLUGIN);
        }


        $key = get_string('SYS_SETTINGS_VISUAL_COLORBAR', self::PLUGIN);
        if ($data[$key] == null) {
            $errors[$key] = get_string('required');

        } else if (!preg_match( self::COLOR_REGEX, $data[$key])) {
            $errors[$key] = get_string('color', self::PLUGIN);
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

        // Convertimos el objeto en un arreglo y eliminamos las entradas
        // que no pertenecen a las configuraciones del plugin
        $keys = get_object_vars($changes);
        $filekey = get_string('SYS_SETTINGS_VISUAL_IMAGE', self::PLUGIN);
        $filename = $this->get_new_filename($filekey);
        unset($keys['submitbutton']);
        unset($keys[$filekey]);

        // if ( file->provided )
        if( is_string($filename) && $this->update_image_file($filename)) {
            $keys[$filekey] = $filename;
        }

        foreach ($keys as $key => $value) {
            set_config($key, $value, self::PLUGIN);
        }
    }

    private function update_image_file(): bool {

        // Obtenemos el nombre del archivo y la ruta
        global $CFG;
        $filekey  = get_string('SYS_SETTINGS_VISUAL_IMAGE', self::PLUGIN);
        $filepath = get_String('SYS_SETTINGS_VISUAL_IMAGE_PATH', self::PLUGIN);
        $filename = $this->get_new_filename($filekey);
        $this->file_error = false;

        if ( $filename == self::RESERVED_IMAGE_NAME1 ||
          $filename == self::RESERVED_IMAGE_NAME2) {
            $this->file_error = true;
            $this->error = get_string('VISUAL_SETTING_ERROR_IMAGE_NAME', self::PLUGIN);
            return false;
        }

        // Guardamos el archivo con override = true
        $saved = $this->save_file($filekey, $CFG->dirroot.$filepath.$filename, false);

        if (!$saved) { // TODO UNCOMMENT file_error = true AND CHECK ERRORS
            //$this->file_error = true;
            $this->error = get_string('VISUAL_SETTING_ERROR_IMAGE_SAVE', self::PLUGIN);
            return false;
        }

        // Obtenemos el nombre del anterior archivo y lo eliminamos
        $oldfilename = get_config( self::PLUGIN, $filekey);
        $deleted = unlink( $CFG->dirroot. $filepath. $oldfilename);

        if (!$deleted) {
            //$this->file_error = true;
            $this->error= get_string('VISUAL_SETTING_ERROR_IMAGE_DELETE',self::PLUGIN);
        }

        return $saved && $deleted;
    }
}

?>
