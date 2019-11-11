<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/gmtienda/accessmanager_form.php');
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
        /*$display .= html_writer::start_tag('form',
            array('action' => new moodle_url('/local/gmtienda/comprar.php',
                array('gmuserid' => $usuariogamificado->id)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'comprarform'));*/

        $display .= '<input type="hidden" name="objetoid" value=1 />';

        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Comprar', 'class' => "mod_quiz-next-nav btn btn-primary gmtienda-button btn-comprar", "style"=>"margin:auto; width: 25%;"));

        /*$display .= html_writer::end_tag('form');*/
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

        /*
         * Esto es el código para mostrar el popup, todavía no lo hace y ya no se porque, pero de todas maneras lo dejo junto con sus js y archivos por si luego tengo otra idea
         *
         * $method = 'post';

        $url = new moodle_url('/local/gmtienda/comprar.php',
            array('gmuserid' => $usuariogamificado->id));

        $preflightcheckform = new local_gmtienda_preflight_check_form($url->out_omit_querystring(),
            array('hidden' => $url->params()), $method);

        $popupjsoptions = null;
        if ($popuprequired = true ) {
            $action = new popup_action('click', $url, 'popup');
            $popupjsoptions = $action->get_js_options();
        }

        if ($preflightcheckform) {
            $display .= $preflightcheckform->render();
        } else {
            $display .= null;
        }

        $this->page->requires->js_call_amd('local_gmtienda/preflightcheck', 'init',
            array(".btn-comprar [type=submit]", 'Seguro que quieres comprar este item?',
                '#local_gmtienda_preflight_form', $popupjsoptions));*/

        return $display;

    }


}