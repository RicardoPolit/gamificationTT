<?php

defined('MOODLE_INTERNAL') || die();

/**
 * The main renderer for local_gmtienda
 *
 * @package    local_gmtienda
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_gmtienda_renderer extends plugin_renderer_base {

    public function render_main_page2($usuariogamificado){

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



    public function render_main_page($userid, $moodleUserId)
        {
            $objetosBD = $this->obtener_objetos_dependiendo_usuario($userid);
            $objetos = array();
            $tipos = array();
            $ultimoId = 0;
            $seleccionado = array();
            foreach($objetosBD  as $objetoBD)
                {
                    if($objetoBD->tipo > $ultimoId)
                        {
                            $objetos[] = array();
                            $tipos[] = $objetoBD->nombre_tipo;
                            if($objetoBD->tipo == 1)
                                {
                                    $seleccionado[] = 'pix/default.png';
                                }
                            else
                                {
                                    $seleccionado[] = '';
                                }
                            
                        }
                    if(!is_null($objetoBD->elegido))
                        {
                            if($objetoBD->elegido==1)
                                {
                                    $seleccionado[$objetoBD->tipo -1] = $objetoBD->valor;
                                }
                        }
                    $objetos[$objetoBD->tipo -1][]= $objetoBD;
                    $ultimoId = $objetoBD->tipo;
                }
            $datosUsuario = $this->obtener_datos_usuario($moodleUserId);

            $html = "<link href='styles.css' rel='stylesheet' type='text/css'>";
            $html.= html_writer::start_tag('div', array('class'=>'gmtienda-container-tienda'));
                $html.= html_writer::start_tag('div', array('class'=>'gmtienda-container-muestra'));
                    $html.= html_writer::start_tag('div', array('class'=>'gmtienda-container-muestra-actual'));
                        $html.= html_writer::nonempty_tag('h1', 'Visualización actual', array());

                        $html.= html_writer::start_tag('div', array("class"=>"gmtienda-container-muestra-card"));
                            $html.= html_writer::empty_tag('img', array('id'=>"gmtienda-objeto-muestra-ant", "src"=>$seleccionado[0], "class"=> 'gmtienda-objeto-imagen '.$seleccionado[1].' '.$seleccionado[2]));
                            $html.= html_writer::nonempty_tag('p', 'Nombre: <br> '.$datosUsuario->firstname.' '.$datosUsuario->lastname, array("class"=>"gmcompvs-al-desafiar-nombre"));
                        $html.= html_writer::end_tag('div');
                        $html.= html_writer::start_tag('div', array('class'=>'gmtienda-container-muestra'));
                            $html.= html_writer::nonempty_tag('button', 'Cancelar', array('id'=>'gmtienda-boton-cancelar'));
                        $html.= html_writer::end_tag('div');

                    $html.= html_writer::end_tag('div');

                    $html.= html_writer::start_tag('div', array("class"=>"gmtienda-container-monedas-disponibles"));
                    $html.= html_writer::nonempty_tag('h5', 'Monedas disponibles', array());
                        $html.= html_writer::start_tag('div', array('class'=>'gmtienda-monedas-disponibles'));
                        $html.= html_writer::empty_tag('img', array("src"=>"pix/monedas.png", "class"=> 'gmtienda-imagen-monedas'));
                        $html.= html_writer::nonempty_tag('h4', '<b>'.$datosUsuario->monedas_plata.'</b>', array("style"=>"margin: 0%;"));
                        $html.= html_writer::end_tag('div');
                    $html.= html_writer::end_tag('div');

                    $html.= html_writer::start_tag('div', array('class'=>'gmtienda-container-muestra-nuevo'));
                        $html.= html_writer::nonempty_tag('h1', 'Nueva visualización', array());

                        $html.= html_writer::start_tag('div', array("class"=>"gmtienda-container-muestra-card"));
                            $html.= html_writer::empty_tag('img', array("src"=>"pix/default.png", "id"=> 'gmtienda-objeto-muestra', "class"=> 'gmtienda-objeto-imagen '));
                            $html.= html_writer::nonempty_tag('p', 'Nombre: <br> Nombre_del_usuario', array("class"=>"gmcompvs-al-desafiar-nombre"));
                        $html.= html_writer::end_tag('div');
                        $html.= html_writer::start_tag('div', array('class'=>'gmtienda-container-muestra'));
                            $html.= html_writer::nonempty_tag('button', 'Deshacer', array('id'=>'gmtienda-boton-limpiar'));
                            $html.= html_writer::nonempty_tag('button', 'Guardar', array('id'=>'gmtienda-boton-guardar'));
                        $html.= html_writer::end_tag('div');
                    $html.= html_writer::end_tag('div');
                $html.= html_writer::end_tag('div');
                $html.= html_writer::start_tag('div', array('class'=>'gmtienda-container-tipos-objetos'));
                for($i =0; $i<sizeof($objetos); $i++)
                    {
                        $html.= html_writer::nonempty_tag('h1', $tipos[$i], array('class'=>'gmtienda-nombre-tipo'));
                        $html.= $this->renderizar_objetos_tipo_imagen($objetos[$i], $userid);
                    }
                $html.= html_writer::end_tag('div');
            $html.= html_writer::end_tag('div');
            return $html;
        }
    private function renderizar_objetos_tipo_imagen($objetos, $userid)
        {
            $html = html_writer::start_tag('div', array('class'=>'gmtienda-container-objetos-imagen'));
            foreach($objetos as $objeto)
                {
                    $boton = $this->obtener_boton_asociado_objeto($objeto, $userid);
                    $imagen = '';
                    if($objeto->tipo == 1)
                        {
                            $imagen = html_writer::empty_tag('img', array("src"=>$objeto->valor, "class"=> 'gmtienda-objeto-imagen'));
                        }
                    else
                        {
                            $imagen = html_writer::empty_tag('img', array("src"=>"pix/default.png", "class"=> 'gmtienda-objeto-imagen '.$objeto->valor));
                        }
                    $disponible = 0;
                    if(!is_null($objeto->elegido))
                        {
                            $disponible = 1;
                        }
                    $html.= html_writer::start_tag('div', array('class'=>'gmtienda-container-objeto gmtienda-color-fondo-rareza-'.$objeto->rareza, "data-objeto"=>$objeto->ide, "data-tipo"=>$objeto->tipo, 'value'=>$objeto->valor, "data-disponible"=>$disponible));
                    $html.= html_writer::tag('p', $objeto->objeto, array());
                    $html.= $imagen;
                    $html.= $boton;
                    $html.= html_writer::end_tag('div');

                }
            $html.= html_writer::start_tag('div', array());
            $html.= html_writer::end_tag('div');
            $html.= html_writer::end_tag('div');
            return $html;
        }

    private function obtener_boton_asociado_objeto($objeto, $userid)
        {
            $html = '';
            if(is_null($objeto->elegido))
                {
                    $html.= html_writer::start_tag('form',
                    array('action' => new moodle_url('/local/gmtienda/comprar.php',
                        array('gmuserid' => $userid)), 'method' => 'post',
                        'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                        'id' => 'comprarform', "class"=>"gmtienda-boton-compra-form"));
        
                    $html .= '<input type="hidden" name="objetoid" value='.$objeto->ide.' />';
                    $html.= html_writer::start_tag('button', array('class'=>'gmtienda-boton-compra'));
                    $html.= html_writer::empty_tag('img', array("src"=>"pix/monedas.png", "class"=> 'gmtienda-imagen-monedas-boton'));
                    $html.= html_writer::nonempty_tag('p', ''.$objeto->costo, array("style"=>"margin: 0%;"));
                    $html.= html_writer::end_tag('button');
                    $html.= html_writer::end_tag('form');
                } 
            /*else if($objeto->elegido == 0)
                {
                    $html.= html_writer::start_tag('button', array('class'=>'gmtienda-boton-elegir-'.$objeto->tipo));
                    $html.= html_writer::nonempty_tag('p', 'Elegir', array());
                    $html.= html_writer::end_tag('button');
                }*/
            else
                {
                    $html.= html_writer::start_tag('button', array());
                    $html.= html_writer::nonempty_tag('p', ' ', array());
                    $html.= html_writer::end_tag('button');
                }
            return $html;
        }


    private function obtener_objetos_dependiendo_usuario($usuario)
        {
            global $DB;
            $sql = " SELECT gmdl_rareza_objeto_id as rareza, gmdl_tipo_objeto_id as tipo, {gmdl_rareza_objeto}.costo_adquisicion as costo, {gmdl_objeto}.nombre as objeto, {gmdl_tipo_objeto}.nombre as nombre_tipo, valor, elegido, {gmdl_objeto}.id as ide";
            $sql.= " FROM {gmdl_objeto}";
            $sql.= " JOIN {gmdl_tipo_objeto}";
            $sql.= " ON {gmdl_tipo_objeto}.id = {gmdl_objeto}.gmdl_tipo_objeto_id";
            $sql.= " JOIN {gmdl_rareza_objeto}";
            $sql.= " ON {gmdl_rareza_objeto}.id = {gmdl_objeto}.gmdl_rareza_objeto_id";
            $sql.= " LEFT JOIN {gmdl_objeto_desbloqueado}";
            $sql.= " ON {gmdl_objeto}.id = {gmdl_objeto_desbloqueado}.gmdl_objeto_id";
            $sql.= " ORDER BY 2, 1, 6 DESC";
            return $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);
        }

    private function obtener_datos_usuario($moodleUserId)
        {
            global $DB;
            $sql = " SELECT username, firstname, lastname, monedas_plata";
            $sql.= " FROM {user}";
            $sql.= " JOIN {gmdl_usuario}";
            $sql.= " ON {user}.id = {gmdl_usuario}.mdl_id_usuario";
            $sql.= " WHERE {user}.id = $moodleUserId";
            return $DB->get_record_sql($sql, null, $limitfrom = 0, $limitnum = 0);
        }
}