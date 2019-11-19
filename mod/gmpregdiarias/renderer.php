<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The main renderer for mod_gmpregdiarias
 *
 * @package    mod_gmpregdiarias
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The main renderer for mod_gmpregdiarias
 *
 * @package    mod_gmpregdiarias
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_gmpregdiarias_renderer extends plugin_renderer_base {
    /**
     * Initialises the game and returns its HTML code
     *
     * @param stdClass $gmpregdiarias The gmpregdiarias to be added
     * @param context $context The context
     * @return string The HTML code of the game
     */
    public function render_questions($gmpregdiarias, $cm,$userid,$gmuserid,$id) {
        global $DB;

        $categoryid = explode(',', $gmpregdiarias->mdl_question_categories_id)[0];

        $questionids = array_keys($DB->get_records('question', array('category' => intval($categoryid)), '', 'id'));

        $context = context_module::instance($cm->id);

        /*$quizobj = quiz::create($cm->instance, $userid);*/

        $quba = question_engine::make_questions_usage_by_activity('mod_gmpregdiarias', $context);

        /*return $quizobj->get_quiz()->preferredbehaviour;*/

        $quba->set_preferred_behaviour('deferredfeedback');

        $preguntascontestadas = $this->obtener_preguntas_contestadas($gmuserid,$gmpregdiarias->id);

        $preguntaseleccionada = false;

        for($i = 0; $i < sizeof($questionids); $i++){

            $num = rand(0, sizeof($questionids)-1);
            $actualquestionid = $questionids[$num];
            $questiondata = question_bank::load_question_data($actualquestionid);
            $sepuedecontestar = $this->pregunta_no_contestada($preguntascontestadas,$actualquestionid);
            if(($questiondata->qtype == "multichoice" || $questiondata->qtype == "truefalse" || $questiondata->qtype == "shortanswer" || $questiondata->qtype == "numerical") && $sepuedecontestar){
                $question = question_bank::make_question($questiondata);
                $idstoslots[] = $quba->add_question($question, $questiondata->maxmark);
                $preguntaseleccionada = true;
                break;
            }

        }

        if($preguntaseleccionada){

            $values = (object)[
                'gmdl_usuario_id' => $gmuserid,
                'mdl_question_id' => $actualquestionid,
                'gmdl_preg_diarias_id' => $gmpregdiarias->id,
                'calificacion' => 0,
                'fecha' => time()
            ];

            $intentoid = $DB->insert_record('gmdl_intento_diario',$values);

            $quba->start_all_questions();

            question_engine::save_questions_usage_by_activity($quba);


            $display = html_writer::start_tag('form',
                array('action' => new moodle_url('/mod/gmpregdiarias/processattempt.php',
                    array('id' => $quba->get_id(),'cm' => $cm->instance,'userid' => $userid,'gmuserid' => $gmuserid)), 'method' => 'post',
                    'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                    'id' => 'responseform'));

            /*$this->page->requires->js_init_call('M.core_question_engine.init_form',
                array('#responseform'), false, 'core_question_engine');*/

            $display .= '<input type="hidden" name="slots" value="' . implode(',', $idstoslots) . "\" />\n";
            $display .= '<input type="hidden" name="scrollpos" value="" />';
            $display .= '<input type="hidden" name="intentoid" value='.$intentoid.' />';
            $display .= '<input type="hidden" name="idredirect" value='.$id.' />';

            $options = new question_display_options();
            $options->marks = question_display_options::MAX_ONLY;
            $options->markdp = 2; // Display marks to 2 decimal places.
            $options->feedback = question_display_options::VISIBLE;
            $options->generalfeedback = question_display_options::HIDDEN;

            $display .= html_writer::start_tag('div');

            foreach ($idstoslots as $displaynumber => $slot) {
                $display .= $quba->render_question($slot, $options, $displaynumber);
            }


            $display .= html_writer::start_tag('div', array("class"=>"gmpregdiarias-container"));

            $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
                'value' => 'Terminar', 'class' => "mod_quiz-next-nav btn btn-primary gmpregdiarias-button", "style"=>"margin:auto; width: 25%;"));
            $display .= html_writer::end_tag('div');

            $display .= html_writer::end_tag('div');
            $display .= html_writer::end_tag('form');

        }else{

            $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";

            $display .= html_writer::start_tag('div');

            $display .= html_writer::start_tag('h2',array('class' => 'gmpregdiarias-titulo'));

            $display .= html_writer::start_tag('b');

            $display .= 'Ya contestaste todas las preguntas!, contacta a tu profesor para que agregue m&aacute;s';

            $display .= html_writer::end_tag('b');

            $display .= html_writer::end_tag('h2');

            $display .= html_writer::end_tag('div');

        }



        return $display;
    }


    public function render_results_attempt($userScore,$cmid){

        if($userScore > 5 ){

            $mensaje = 'Felicidades! Pregunta diaria contestada correctamente!';
            $class = 'ganador';

        }else{

            $mensaje = 'Oh no!, fallaste la pregunta diaria ';
            $class = 'perdedor';

        }

        $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";

        $display .= html_writer::start_tag('div', array('id'=> 'gmpregdiarias-activity-container'));

            $display .= html_writer::start_tag('h2',array('class' => 'gmpregdiarias-titulo-'.$class));

                $display .= html_writer::start_tag('b');

                    $display .= $mensaje;

                $display .= html_writer::end_tag('b');

            $display .= html_writer::end_tag('h2');


            $display .= html_writer::start_tag('form',
            array('action' => new moodle_url('/mod/gmpregdiarias/view.php',
                array('id' => $cmid)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'responseform'));
            $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
                'value' => 'Volver', 'class' => "btn btn-primary gmpregdiarias-button"));
            $display .= html_writer::end_tag('form');

        $display .= html_writer::end_tag('div');
        return $display;

    }
    public function render_main_page($gmpregdiarias, $userid, $id)
        {
            $moodleUserId = $userid;
            $userid = $this->obtener_usuario_gamedle($moodleUserId);
            if(is_null($userid))
                {
                    $userid = $this->insertar_usuario($moodleUserId);
                }

            $preguntasDisponibles = $this->obtener_num_preguntas_disponibles($gmpregdiarias->id);
            $preguntasContestadas = $this->obtener_num_contestadas_usuario($gmpregdiarias->id, $userid);
            $sepuedecontestar = $this->pregunta_no_contestada_hoy($userid,$gmpregdiarias->id);

            $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";

            $this->page->requires->js_call_amd('mod_gmpregdiarias/js_preg_diarias', 'init');

            $display.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-contianer-menu-opciones"));
                $display.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-contianer-menu-opcion-activa gmpregdiarias-contianer-menu-opcion-js", "id"=>"gmpregdiarias-contianer-menu-opcion-inicio"));
                    $display.= html_writer::nonempty_tag('h3', 'Inicio',array());
                $display.= html_writer::end_tag('div');
                $display.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-contianer-menu-opcion gmpregdiarias-contianer-menu-opcion-js", "id"=>"gmpregdiarias-contianer-menu-opcion-scores"));
                    $display.= html_writer::nonempty_tag('h3', 'Tabla posiciones',array());
                $display.= html_writer::end_tag('div');
            $display.= html_writer::end_tag('div');
            $display.= html_writer::start_tag('div', array("id"=>"gmpregdiarias-activity-container"));

                $display.= html_writer::start_tag('div', array( "id"=>"gmpregdiarias-contianer-menu-opcion-inicio-container", "class"=>"gmpregdiarias-section-contianer-menu-opcion"));
                $display.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-card-container"));
                $display.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-card"));

                    $display .= html_writer::nonempty_tag('h3', 'Tu progreso', array());
                    $display.= html_writer::nonempty_tag('p', "<br>",array());
                    $display.= html_writer::nonempty_tag('progress',' ',array("id"=>"gmpregdiarias-progress", "value"=>$preguntasContestadas+0.1, "max"=>$preguntasDisponibles));
                    $display.= html_writer::nonempty_tag('p', "$preguntasContestadas/$preguntasDisponibles preguntas respondidas",array("id"=>"gmpregdiarias-progress-values"));
                $display .= html_writer::end_tag('div');
            $display .= html_writer::end_tag('div');

                $display .= html_writer::start_tag('h1',array('class' => 'gmpregdiarias-titulo'));
                    $display .= html_writer::start_tag('b');
                        $display .= 'Preguntas diarias';
                    $display .= html_writer::end_tag('b');
                $display .= html_writer::end_tag('h1');

            $text = '¡Contesta la pregunta diaria de hoy!';
            $imagen = 'pix/reloj.png';
            $boton = '';

            $boton .= html_writer::start_tag('form',
                array('action' => new moodle_url('/mod/gmpregdiarias/attempt.php',
                    array('userid' => $moodleUserId, 'gmuserid' => $userid ,'instance' => $gmpregdiarias->id, 'id' => $id)), 'method' => 'post',
                    'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                    'id' => 'responseform'));

            $boton.= html_writer::empty_tag('input', array("type" =>"submit", "value"=>"Contestar", "class" => "gmpregdiarias-button btn btn-primary gmpregdiarias-container-card-button" ));
            $boton  .= html_writer::end_tag('form');

            if(!$sepuedecontestar)
                {
                    $text = 'La pregunta diaria ya fue contestada. <br>Regresa mañana.';
                    $imagen = 'pix/check.png';
                    $boton = html_writer::nonempty_tag('button', 'Deshabilitado',array("class" => "btn btn-secondary" ));
                }

            $display.= html_writer::start_tag('div', array("id"=>"gmpregdiarias-accion-container"));
                $display.= html_writer::empty_tag('img', array("src"=>$imagen, "class"=> 'gmpregdiarias-imagen'));
                $display.= html_writer::nonempty_tag('h4', $text, array());
                $display.= $boton;
            $display.= html_writer::end_tag('div');
            $display.= html_writer::end_tag('div');





            return $display.$this->render_scores_page($gmpregdiarias, $preguntasDisponibles);
        }



    public function render_scores_page($gmpregdiarias, $preguntasDisponibles)
        {
            $filas = $this->obtener_preguntas_contestadas_todos($gmpregdiarias->id);
            $html = html_writer::start_tag('div', array( "id"=>"gmpregdiarias-contianer-menu-opcion-scores-container", "class"=>"gmpregdiarias-section-contianer-menu-opcion"));
            $html.= html_writer::start_tag('div', array( "id"=>"gmpregdiarias-table-historial-container"));
                $html.= html_writer::start_tag('table', array("class"=>"gmpregdiarias-table", "id"=>"gmpregdiarias-table-historial"));
                    $html.= html_writer::start_tag('thead', array("class"=>"gmpregdiarias-thead"));
                        $html.= html_writer::start_tag('tr', array());
                            $html.= html_writer::nonempty_tag('th', "Posición", array("class"=>"gmpregdiarias-table-posicion-column"));
                            $html.= html_writer::nonempty_tag('th', "Nombre", array("class"=>"gmpregdiarias-table-name-column"));
                            $html.= html_writer::nonempty_tag('th', "Preguntas", array("class"=>"gmpregdiarias-table-preguntas-column"));
                        $html.= html_writer::end_tag('tr', array());
                    $html.= html_writer::end_tag('thead', array());
                    $html.= html_writer::start_tag('tbody', array("class"=>"gmpregdiarias-tbody"));
            $lugar = 1;
            $primeraFila = 1;
            $ultimosPuntos = 0;
            foreach($filas as $fila)
                {
                    if($ultimosPuntos == $fila->preguntas && $primeraFila==0)
                        {
                            $lugar--;
                        }
                    $html.= html_writer::start_tag('tr');
                        $html.= html_writer::start_tag('td', array("class"=>"gmpregdiarias-table-posicion-column"));
                            if($lugar == 1){ $html.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmpregdiarias-trohpy-image")); }
                            else if($lugar == 2){ $html.= html_writer::empty_tag('img', array("src"=>"pix/trophy_second.png", "class"=>"gmpregdiarias-trohpy-image")); }
                            else if($lugar == 3){ $html.= html_writer::empty_tag('img', array("src"=>"pix/trophy_third.png", "class"=>"gmpregdiarias-trohpy-image")); }
                            else{ $html.= html_writer::nonempty_tag('p',$lugar."°" ,array("")); }
                        $html.= html_writer::end_tag('td', array());
                        $html .= html_writer::start_tag('td',array("class"=>"gmpregdiarias-table-name-column"));
                        $seleccionado = $this->obtener_objetos_dependiendo_usuario($fila->gmuserid);

                        $html .= html_writer::start_tag('div', array("class"=>"gmtienda-muestra-en-tabla"));
                        $html .= html_writer::empty_tag('img', array( "src"=>new moodle_url('/local/gmtienda/'.$seleccionado[0]['valor']), "class"=> 'gmcompvs-profile-image '.$seleccionado[1]['valor'].' '.$seleccionado[2]['valor']));
                        $html .= html_writer::nonempty_tag('p',$fila->firstname.' '.$fila->lastname.' ('.$fila->username.')' ,array(""));
                        $html .= html_writer::end_tag('div');
                        $html .= html_writer::end_tag('td');
                        $html.= html_writer::start_tag('td', array("class"=>"gmpregdiarias-table-preguntas-column"));
                        $html.= html_writer::nonempty_tag('progress',' ',array("id"=>"gmpregdiarias-progress", "value"=>$fila->preguntas+0.1, "max"=>$preguntasDisponibles));
                        $html.= html_writer::nonempty_tag('p', "$fila->preguntas/$preguntasDisponibles preguntas respondidas",array("id"=>"gmpregdiarias-progress-values"));
                        $html.= html_writer::end_tag('td', array());

                    $html.= html_writer::end_tag('tr', array());
                    if($primeraFila == 1)
                        {
                            $primeraFila = 0;
                        }
                    $ultimosPuntos = $fila->preguntas;
                    $lugar++;

                }

            if($primeraFila == 1)
                {
                    $html.= html_writer::start_tag('tr', array("class"=>"gmpregdiarias-no-lugar"));
                    $html.= html_writer::nonempty_tag('td', "0°", array("class"=>"gmpregdiarias-table-posicion-column"));
                    $html.= html_writer::nonempty_tag('td', "Sin participantes", array("class"=>"gmpregdiarias-table-name-column"));
                    $html.= html_writer::nonempty_tag('td', " - - - ", array("class"=>"gmpregdiarias-table-preguntas-column"));
                    $html.= html_writer::end_tag('tr');
                }
                $html.= html_writer::end_tag('tbody');
                $html.= html_writer::end_tag('table');
                $html.= html_writer::end_tag('div');
                $html.= html_writer::end_tag('div');
                $html.= html_writer::end_tag('div');
            return $html;
        }

    private function obtener_num_preguntas_disponibles($instancia)
        {
            global $DB;
            $categorias = $DB->get_record('gmpregdiarias', array("id"=>$instancia), $fields='*', $strictness=MUST_EXIST)->mdl_question_categories_id;
            $idCategoria = explode(',', $categorias)[0];
            $sql = " SELECT COUNT({question}.id)";
            $sql.= " FROM {question}";
            $sql.= " JOIN {question_categories} ON {question_categories}.id = {question}.category";
            $sql.= " WHERE {question_categories}.id = $idCategoria AND";
            $sql.= " {question}.qtype IN ('multichoice', 'truefalse', 'shortanswer', 'numerical')";
            return $DB->count_records_sql($sql, null);
        }

    private function obtener_num_contestadas_usuario($instancia, $userid)
        {
            global $DB;
            return $DB->count_records('gmdl_intento_diario', array("gmdl_preg_diarias_id"=>$instancia, "gmdl_usuario_id"=>$userid));
        }

    private function obtener_preguntas_contestadas_todos($instancia)
        {
            global $DB;
            $sql = " SELECT username, firstname, lastname, a.preguntas, a.gmdl_usuario_id as gmuserid";
            $sql.= " FROM {user}";
            $sql.= " JOIN {gmdl_usuario} ON {user}.id = {gmdl_usuario}.mdl_id_usuario";
            $sql.= " JOIN";
            $sql.= " (SELECT gmdl_usuario_id, COUNT(id) as preguntas";
            $sql.= " FROM {gmdl_intento_diario}";
            $sql.= " WHERE gmdl_preg_diarias_id = $instancia";
            $sql.= " GROUP BY gmdl_usuario_id) as a";
            $sql.= " ON a.gmdl_usuario_id = {gmdl_usuario}.id";
            return $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);
        }

    private function pregunta_no_contestada_hoy($userid,$instancia){

            global $DB;

            $sql = "SELECT * from {gmdl_intento_diario}";
            $sql .= " WHERE {gmdl_intento_diario}.gmdl_usuario_id =".$userid." AND";
            $sql .= " {gmdl_intento_diario}.gmdl_preg_diarias_id =".$instancia;
            $sql .= " ORDER BY {gmdl_intento_diario}.id DESC LIMIT 1";

            $rows = $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);

            $flag = true;

            foreach ($rows as $row){

                $fechaultima = explode('-',date('Y-m-d',$row->fecha));
                $fechaactual = explode('-',date('Y-m-d',time()));

                if($fechaultima[0] == $fechaactual[0] && $fechaultima[1] == $fechaactual[1] && $fechaultima[2] == $fechaactual[2]){
                    $flag = false;
                }

            }

            return $flag;

        }

    private function obtener_preguntas_contestadas($userid,$instancia){

        global $DB;

        $sql = "SELECT * from {gmdl_intento_diario}";
        $sql .= " WHERE {gmdl_intento_diario}.gmdl_usuario_id = ".$userid." AND";
        $sql .= " {gmdl_intento_diario}.gmdl_preg_diarias_id = ".$instancia; /*." AND";
        $sql .= " {gmdl_intento_diario}.calificacion > 5";*/

        $rows = $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);

        return $rows;

    }

    private function obtener_objetos_dependiendo_usuario($usuario)
    {
        global $DB;
        $sql = " SELECT ";
        $sql.= "    gmdl_rareza_objeto_id as rareza,";
        $sql.= "    gmdl_tipo_objeto_id as tipo,";
        $sql.= "    {gmdl_rareza_objeto}.costo_adquisicion as costo,";
        $sql.= "    {gmdl_objeto}.nombre as objeto,";
        $sql.= "    {gmdl_tipo_objeto}.nombre as nombre_tipo,";
        $sql.= "    valor,";
        $sql.= "    elegido,";
        $sql.= "    {gmdl_objeto}.id as ide";
        $sql.= " FROM {gmdl_objeto}";
        $sql.= " JOIN {gmdl_tipo_objeto}";
        $sql.= " ON {gmdl_tipo_objeto}.id = {gmdl_objeto}.gmdl_tipo_objeto_id";
        $sql.= " JOIN {gmdl_rareza_objeto}";
        $sql.= " ON {gmdl_rareza_objeto}.id = {gmdl_objeto}.gmdl_rareza_objeto_id";
        $sql.= " LEFT JOIN {gmdl_objeto_desbloqueado}";
        $sql.= " ON {gmdl_objeto}.id = {gmdl_objeto_desbloqueado}.gmdl_objeto_id AND gmdl_usuario_id = $usuario ";
        $sql.= " ORDER BY 2, 1, 6 DESC";
        $objetosBD = $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);

        $ultimoId = 0;

        foreach($objetosBD  as $objetoBD) {
            if($objetoBD->tipo > $ultimoId)
            {
                $objetos[] = array();
                $tipos[] = $objetoBD->nombre_tipo;
                if($objetoBD->tipo == 1)
                {
                    $seleccionado[] = array("valor"=>'pix/default.png', "id"=>0);
                }
                else
                {
                    $seleccionado[] = array("valor"=>'', "id"=>0);
                }

            }
            if(!is_null($objetoBD->elegido))
            {
                if($objetoBD->elegido==1)
                {
                    $seleccionado[$objetoBD->tipo -1] = array("valor"=>$objetoBD->valor, "id"=>$objetoBD->ide);
                }
            }
            $objetos[$objetoBD->tipo -1][]= $objetoBD;
            $ultimoId = $objetoBD->tipo;
        }

        return $seleccionado;

    }

    private function pregunta_no_contestada($preguntas,$questionid){

        $flag = true;

        foreach ($preguntas as $pregunta){

            if( $pregunta->mdl_question_id == $questionid ){
                $flag = false;
            }

        }

        return $flag;
    }

    private function obtener_usuario_gamedle($moodleUserId)
        {
            global $DB;
            return $DB->get_record('gmdl_usuario', $conditions=array("mdl_id_usuario" => $moodleUserId), $fields='*', $strictness=IGNORE_MISSING)->id;
        }

    

    private function insertar_usuario($usuario)
        {
            global $DB;
            $data = new stdClass();
            $data->mdl_id_usuario = $usuario;
            $data->nivel_actual = 1;
            $data->experiencia_actual = 0;
            return $DB->insert_record('gmdl_usuario', $data, $returnid=true, $bulk=false);
        }
}
