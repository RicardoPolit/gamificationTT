<?php

defined('MOODLE_INTERNAL') || die();

/**
 * The main renderer for local_gmtienda
 *
 * @package    local_gmtienda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_gmtienda_renderer extends plugin_renderer_base {

    public function render_main_page($usuariogamificado){

        $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";
        $display .= html_writer::start_tag('div',array('class' => 'gmtienda-container'));
        $display .= html_writer::start_tag('div',array('class' => 'gmtienda-half-container'));
        $display .= html_writer::start_tag('form',
            array('action' => new moodle_url('/local/gmtienda/comprar.php',
                array('gmuserid' => $usuariogamificado->id)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'comprarform'));

        $display .= '<input type="hidden" name="objetoid" value=1 />';

        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Comprar', 'class' => "mod_quiz-next-nav btn btn-primary gmtienda-button btn-primary-js", "style"=>"margin:auto; width: 25%;"));
        $display .= html_writer::end_tag('form');
        $display .= html_writer::end_tag('div');
        $display .= html_writer::start_tag('div',array('class' => 'gmtienda-half-container'));
        $display .= html_writer::start_tag('form',
            array('action' => new moodle_url('/local/gmtienda/elegir.php',
                array('gmuserid' => $usuariogamificado->id)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'elegirform'));

        $display .= '<input type="hidden" name="objetoid" value=1 />';

        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Elegir', 'class' => "mod_quiz-next-nav btn btn-primary gmtienda-button btn-primary-js", "style"=>"margin:auto; width: 25%;"));
        $display .= html_writer::end_tag('form');
        $display .= html_writer::end_tag('div');

        return $display;

    }

}