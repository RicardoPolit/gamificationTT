<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *   
 * TRABAJO TERMINAL: 2018-B029
 *   "GAMIFICACIÓN EN UNA PLATAFORMA WEB DE APRENDIZAJE"
 *
 *
 * ASESORES: Sandra I, Bautista, Edgar A. Catatán
 *
 * DESARROLLADORES:
 *   - Daniel Ortega  (gitlab: DanielOrtegaZ)
 *   - Ricardo, David
 *
 *
 * NOTES:
 *   In order to provide this form element the support for
 *   client side validation, the following line of code was
 *   added at start of the method toHTML().
 *  
 *   <code>
 *       $this->_type = 'text'"
 *   </code>
 * 
 *   Also, Some restrictions where added/modified to the input
 *   html tag.
 * 
 *   <code>
 *       'size' => 7,     // Modified
 *       'maxlength' => 7 // Hex number format (e.g #OB1ED9)
 *   </code>
 *
 *
 *
 * The source code without modifications could be found in
 * https://github.com/markn86/moodle-mod_customcert,
 * thanks to markn86
*/

defined('MOODLE_INTERNAL') || die('Direct access to this script is forbidden.');

global $CFG;
require_once($CFG->dirroot . '/lib/form/editor.php');

/**
 * Form element for handling the colour picker.
 *
 * @package    mod_customcert
 * @copyright  2013 Mark Nelson <markn@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class moodlequickform_customcert_colourpicker extends moodlequickform_editor {

    /**
     * Sets the value of the form element
     *
     * @param string $value
     */
    public function setvalue($value) {
        $this->updateAttributes(array('value' => $value));
    }

    /**
     * Gets the value of the form element
     */
    public function getvalue() {
        return $this->getAttribute('value');
    }

    /**
     * Returns the html string to display this element.
     *
     * @return string
     */
    public function tohtml() {

        $this->_type = 'text'; // THIS LINE MODIFIED FOR CLIENT SIDE VALIDATION
        global $PAGE, $OUTPUT;

        $PAGE->requires->js_init_call('M.util.init_colour_picker',
            array($this->getAttribute('id'), null));

        $content = "";
        $content .= html_writer::tag('label', $this->getLabel(), array(
            'class' => 'accesshide',
            'for' => $this->getAttribute('id'),
        ));

        $content .= html_writer::start_tag('div', array(
            'class' => 'form-colourpicker defaultsnext'
        ));

            $pix_icon = $OUTPUT->pix_icon( 'i/loading', get_string('loading', 'admin'), 'moodle', array(
                'class' => 'loadingicon'
            ));

            $content .= html_writer::tag('div', $pix_icon, array(
                'class' => 'admin_colourpicker clearfix'
            ));

            $content .= html_writer::empty_tag('input', array(
                'type' => 'text',
                'id' => $this->getAttribute('id'),
                'name' => $this->getName(),
                'value' => $this->getValue(),
                'size' => 7,
                'maxlength' => 7,
            ));

        $content .= html_writer::end_tag('div');

        return $content;
    }

    /**
     * Function to export the renderer data in a format that is suitable for a mustache template.
     *
     * @param \renderer_base $output Used to do a final render of any components that need to be rendered for export.
     * @return \stdClass|array
     */
    public function export_for_template(renderer_base $output) {
        $context = $this->export_for_template_base($output);
        $context['html'] = $this->toHtml();

        return $context;
    }
}
