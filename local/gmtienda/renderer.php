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

        $botones = array();

        $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";

        $display .= html_writer::start_tag('div',array('class' => 'gmtienda-container'));
        $display .= html_writer::start_tag('div',array('class' => 'gmtienda-half-container'));

        $display .= html_writer::start_tag('form',
            array('action' => new moodle_url('/local/gmtienda/comprar.php',
                array('gmuserid' => $usuariogamificado->id)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'responseform'));

        $display .= '<input type="hidden" name="objetoid" value=1 />';

        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Comprar', 'class' => "mod_quiz-next-nav btn btn-primary gmtienda-button btn-comprar", 'id' => "btn-comprar-1" , "style"=>"margin:auto; width: 25%;"));

        $display .= html_writer::end_tag('form');


        $display .= html_writer::end_tag('div');
        $display .= html_writer::start_tag('div',array('class' => 'gmtienda-half-container'));

        $display .= html_writer::start_tag('form',
            array('action' => new moodle_url('/local/gmtienda/comprar.php',
                array('gmuserid' => $usuariogamificado->id)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'responseform'));

        $display .= '<input type="hidden" name="objetoid" value=1 />';

        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Elegir', 'class' => "mod_quiz-next-nav btn btn-primary gmtienda-button btn-primary-js btn-elegir", 'id' => "btn-elegir-2", "style"=>"margin:auto; width: 25%;"));

        $display .= html_writer::end_tag('form');

        $display .= html_writer::end_tag('div');
        $display .= html_writer::end_tag('div');

        /*
         *
         * Se comenta las siguientes lineas porque el popup todavia no funciona bien, solo muestra el ultimo popup sin importar que boton fue presionado,
         * se le podria poner uno solo de confimacion?
         *
         * */

        /*$botones[] = (object)[
            'tipo' => 'comprar',
            'objetoid' => 1
        ];

        $botones[] = (object)[
            'tipo' => 'elegir',
            'objetoid' => 2
        ];


        $display .= $this->render_allpopups($botones,$usuariogamificado->id);*/


        return $display;

    }

    private function render_allpopups( $botones , $gmuserid){
        $display = '';

        $display .= html_writer::start_tag('div',array('class' => 'gmtienda-popups-container'));

        foreach ( $botones as $boton ){

            $display .= html_writer::start_tag('div',array('class' => "gmtienda-popup-container-$boton->tipo-$boton->objetoid"));

            $display .= $this->render_popup(new moodle_url("/local/gmtienda/$boton->tipo.php",
                array('gmuserid' => $gmuserid, 'objetoid' => $boton->objetoid )),
                "btn-$boton->tipo-$boton->objetoid",
                $boton->objetoid,
                "Seguro que quieres $boton->tipo el objeto $boton->objetoid?",
                $boton->tipo
            );

            $display .= html_writer::end_tag('div');
        }

        $display .= html_writer::end_tag('div');

        return $display;

    }

    private function render_popup($url,$id, $objetoid ,$mensaje = "Confirmar la accion",$nombreboton = "Confirmar"){

        $method = 'post';

        $preflightcheckform = new local_gmtienda_preflight_check_form($url->out_omit_querystring(),
            array('hidden' => $url->params(),'nombreboton' => $nombreboton, 'objetoid' => $objetoid), $method);

        if ($preflightcheckform) {
            $display = $preflightcheckform->render();
        } else {
            $display = null;
        }

        $this->page->requires->js_call_amd('local_gmtienda/preflightcheck', 'init',
            array("#$id", $mensaje,
                "#popup-$nombreboton-$objetoid"));

        return $display;
    }


}