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

abstract class local_gamedlemaster_form extends moodleform {

    protected $form_title = 'FORM TITLE';
    protected $form_subtitle = 'FORM SUBTITLE';
    protected $form_description = 'FORM DESCRIPTION';

    protected $has_error = false;
    protected $error = 'ERROR';
    protected $form_success;

    function __construct() {
        parent::__construct();
        $this->form_success = get_string('FORM_SUCCESS', 'local_gamedlemaster');
        $this->error = get_string('FORM_ERROR', 'local_gamedlemaster');
    }

    function set_title($title) {
        $this->form_title = $title;
    }

    function set_subtitle($subtitle) {
        $this->form_subtitle = $subtitle;
    }

    function set_description($desc) {
        $this->form_description = $desc;
    }

    function render() {
        $output = $this->render_message();
        $output .= $this->render_header();
        $output .= parent::render();
        return $output;
    }

    function render_message() {
        global $OUTPUT;
        $output = '';

        if ($this->is_submitted()) {
            if ($this->has_error) {
                $output = $OUTPUT->notification($this->error);

            } else {
                $output = $OUTPUT->notification($this->form_success, 'notifysuccess');
            }
        }

        return $output;
    }

    function render_header() {
        $output = html_writer::tag('h2', $this->form_title);
        $output.= html_writer::tag('h5', $this->form_subtitle,
            array('style' => 'line-height: 3;border-bottom: 1px solid #e5e5e5;'));

        $output.= html_writer::tag('p', $this->form_description);

        return $output;
    }

    function render_problem(string $error, string $text, string $link) {

        global $OUTPUT;

        $output = '';
        $output.= html_writer::tag('h2', $this->form_title);
        $output.= $OUTPUT->notification($error);
        $output.= html_writer::start_tag('a', array('href' => $link));
        $output.= html_writer::empty_tag('input', array(
            'class' => "btn btn-primary",
            'value' => $text,
            'style' => 'margin:auto; display: block'
        ));
    
        $output.= html_writer::end_tag('a');
        return $output;
    }

    function go_site_administration() {
        global $CFG;
        redirect("{$CFG->wwwroot}/admin/search.php");
    }
}

?>
