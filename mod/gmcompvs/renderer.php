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
 * The main renderer for mod_gmcompvs
 *
 * @package    mod_gmcompvs
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The main renderer for mod_gmcompvs
 *
 * @package    mod_gmcompvs
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_gmcompvs_renderer extends plugin_renderer_base {
    /**
     * Initialises the game and returns its HTML code
     *
     * @param stdClass $gmcompvs The gmcompvs to be added
     * @param context $context The context
     * @return string The HTML code of the game
     */

    public function render_wait_page($cmid){

        $display = "<link href='estilos.css' rel='stylesheet' type='text/css'>";
        $display .= html_writer::start_tag('div', array("id"=>"gmcompcpu-activity-container", "class"=>"gmcompcpu-container-results"));
        $display .= html_writer::start_tag('div', array("class"=>"gmcompvs-container-columns", "id"=>"gmcompvs-activity-container"));

        $display .= html_writer::start_tag('h2',array('class' => ''));

        $display .= html_writer::start_tag('b');

        $display .= 'Felicidades terminaste el desaf&iacute;o, el resultado se mostrar&aacute; cuando el contrincante tambi&eacute;n lo termine';

        $display .= html_writer::end_tag('b');

        $display .= html_writer::end_tag('h2');

        $display .= html_writer::end_tag('div');

        $display .= html_writer::start_tag('form',
            array('action' => new moodle_url('/mod/gmcompvs/view.php',
                array('id' => $cmid)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'responseform'));
        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Volver', 'class' => "btn btn-primary gmcompcpu-button"));
        $display .= html_writer::end_tag('form');
        $display .= html_writer::end_tag('div');

        return $display;
    }

    public function render_questions($gmcompvs, $cm,$userid,$participacionid,$gmuserid,$id,$montoapuesta) {
        global $DB;

        $participacion = (object)[
            'id' => $participacionid,
            'fecha_inicio' => time()
        ];

        $DB->update_record('gmdl_participacion',$participacion);

        $usuario = $DB->get_record('gmdl_usuario',array('id' => $gmuserid));

        if( $gmcompvs->apuestas_activas && $montoapuesta != -1 && ($usuario->monedas_plata - $montoapuesta) >= 0 ){

            $usuario->monedas_plata -=  $montoapuesta;

            $DB->update_record('gmdl_usuario',$usuario);

            $apuesta = (object)[
                'gmdl_participacion_id' => $participacionid,
                'activa' => 1,
                'monedas_plata' => $montoapuesta
            ];

            $DB->insert_record('gmdl_apuesta',$apuesta);

        }

        $categoryid = explode(',', $gmcompvs->mdl_question_categories_id)[0];

        $questionids = array_keys($DB->get_records('question', array('category' => intval($categoryid)), '', 'id'));

        $context = context_module::instance($cm->id);

        /*$quizobj = quiz::create($cm->instance, $userid);*/

        $quba = question_engine::make_questions_usage_by_activity('mod_gmcompvs', $context);

        /*return $quizobj->get_quiz()->preferredbehaviour;*/

        $quba->set_preferred_behaviour('deferredfeedback');

        foreach ($questionids as $questionid) {
            $questiondata = question_bank::load_question_data($questionid);

            if($questiondata->qtype == "multichoice" || $questiondata->qtype == "truefalse" || $questiondata->qtype == "shortanswer" || $questiondata->qtype == "numerical"){
                $question = question_bank::make_question($questiondata);
                $idstoslots[] = $quba->add_question($question, $questiondata->maxmark);
            }

        }

        $quba->start_all_questions();

        question_engine::save_questions_usage_by_activity($quba);


        $display = html_writer::start_tag('form',
            array('action' => new moodle_url('/mod/gmcompvs/processattempt.php',
                array('id' => $quba->get_id(),'cm' => $cm->instance,'userid' => $userid)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'responseform'));

        $this->page->requires->js_call_amd('mod_gmcompvs/js_protectclose', 'init');

        $display .= '<input type="hidden" name="slots" value="' . implode(',', $idstoslots) . "\" />\n";
        $display .= '<input type="hidden" name="scrollpos" value="" />';
        $display .= '<input type="hidden" name="participacionid" value='.$participacionid.' />';
        $display .= '<input type="hidden" name="idredirect" value='.$id.' />';
        $display .= '<input type="hidden" name="gmuserid" value='.$gmuserid.' />';

        $options = new question_display_options();
        $options->marks = question_display_options::MAX_ONLY;
        $options->markdp = 2; // Display marks to 2 decimal places.
        $options->feedback = question_display_options::VISIBLE;
        $options->generalfeedback = question_display_options::HIDDEN;

        $display .= html_writer::start_tag('div');

        foreach ($idstoslots as $displaynumber => $slot) {
            $display .= $quba->render_question($slot, $options, $displaynumber);
        }


        $display .= html_writer::start_tag('div', array("class"=>"gmcompvs-container"));

        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Terminar', 'class' => "mod_quiz-next-nav btn btn-primary gmcompvs-button btn-primary-js", "style"=>"margin:auto; width: 25%;"));
        $display .= html_writer::end_tag('div');

        $display .= html_writer::end_tag('div');
        $display .= html_writer::end_tag('form');

        return $display;
    }


    public function render_mensaje($mensaje,$cmid){

        $display = "<link href='estilos.css' rel='stylesheet' type='text/css'>";


            $class = 'ganador';

        $display .= html_writer::start_tag('div', array("id"=>"gmcompcpu-activity-container", "class"=>"gmcompcpu-container-results"));
        $display .= html_writer::start_tag('div', array("class"=>"gmcompvs-container-columns"));
        $display .= html_writer::start_tag('h2',array('class' => 'gmcompvs-titulo-'.$class));
        $display .= html_writer::start_tag('b');
        $display .= $mensaje;
        $display .= html_writer::end_tag('b');
        $display .= html_writer::end_tag('h2');
        $display .= html_writer::end_tag('div');

        $display .= html_writer::start_tag('form',
            array('action' => new moodle_url('/mod/gmcompvs/view.php',
                array('id' => $cmid)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'responseform'));
        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Volver', 'class' => "btn btn-primary gmcompcpu-button"));
        $display .= html_writer::end_tag('form');
        $display .= html_writer::end_tag('div');

        return $display;

    }

    public function render_results_attempt($userScore,$contrincanteScore,$cmid){

        $display = "<link href='estilos.css' rel='stylesheet' type='text/css'>";

        $mensaje = 'El desafío fue un empate!';
        $class = 'ganador';
        if($userScore > $contrincanteScore){

            $mensaje = 'Felicidades! Ganaste el desaf&iacute;o!';
            $class = 'ganador';

        }else if($userScore < $contrincanteScore){

            $mensaje = 'Oh no!, Perdiste el desaf&iacute;o!';
            $class = 'perdedor';

        }
        $display .= html_writer::start_tag('div', array("id"=>"gmcompcpu-activity-container", "class"=>"gmcompcpu-container-results"));
        $display .= html_writer::start_tag('div', array("class"=>"gmcompvs-container-columns"));
        $display .= html_writer::start_tag('h2',array('class' => 'gmcompvs-titulo-'.$class));
        $display .= html_writer::start_tag('b');
        $display .= $mensaje;
        $display .= html_writer::end_tag('b');
        $display .= html_writer::end_tag('h2');
        $display .= html_writer::end_tag('div');
        $display .= html_writer::start_tag('div',array('class' => 'gmcompvs-container'));
        $display .= html_writer::start_tag('div',array('class' => 'gmcompvs-half-container'));

        $display .= html_writer::start_tag('h3');
        $display .= html_writer::start_tag('b');
        $display .= 'Tu puntuaci&oacute;n: '.$userScore;
        $display .= html_writer::end_tag('b');
        $display .= html_writer::end_tag('h3');

        $display .= html_writer::end_tag('div');
        $display .= html_writer::start_tag('div',array('class' => 'gmcompvs-half-container'));
        $display .= html_writer::start_tag('h3');
        $display .= html_writer::start_tag('b');
        $display .= 'Puntuaci&oacute;n contrincante: '.$contrincanteScore;
        $display .= html_writer::end_tag('b');
        $display .= html_writer::end_tag('h3');
        $display .= html_writer::end_tag('div');
        $display .= html_writer::end_tag('div');
        $display .= html_writer::start_tag('form',
            array('action' => new moodle_url('/mod/gmcompvs/view.php',
                array('id' => $cmid)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'responseform'));
        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Volver', 'class' => "btn btn-primary gmcompcpu-button"));
        $display .= html_writer::end_tag('form');
        $display .= html_writer::end_tag('div');

        return $display;

    }

    public function render_main_page($gmcompvs, $userid,$id)
        {

            $moodleUserId = $userid;
            global $DB;
            $userid = $DB->get_record('gmdl_usuario', $conditions=array("mdl_id_usuario" => $userid), $fields='*', $strictness=IGNORE_MISSING)->id;
            if(is_null($userid))
                {
                    $userid = $this->insertar_usuario($moodleUserId);
                }
            $apuestasActivas = $this->obtener_apuestas_activas_instancia($gmcompvs->id);
            $monedasDisponibles = $this->obtener_monedas_usuario($userid);

            $apuestaMonedas = html_writer::start_tag('div', array("class"=>"gmcompvs-al-desafiar-monedas"));
            $apuestaMonedas.= html_writer::nonempty_tag('p', "Monedas a apostar: <br>",array());
            $apuestaMonedas.= html_writer::empty_tag('input', array("type"=>"number", "name"=>"montoapuesta", "step"=>"1", "min"=>'1', "max"=>$monedasDisponibles, "value" => "1"));
            $apuestaMonedas.= html_writer::end_tag('div');

            $this->revisar_partidas_enprogreso($gmcompvs->id,$userid);
            $numVictorias = $this->obtener_dato_sql_victorias($gmcompvs->id, $userid);
            $posiblesRivales = $this->obtener_posibles_rivales($gmcompvs->id, $userid);
            $desafiosPorTerminar = $this->obtener_desafios_pendientes($gmcompvs->id, $userid);
            $this->page->requires->js_call_amd('mod_gmcompvs/js_competencia_vs', 'init');
            $html = "<link href='estilos.css' rel='stylesheet' type='text/css'>";
            $html.= html_writer::tag('div', '',array("class" =>"gmcompvs-linea"));

            $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-contianer-menu-opciones"));
                $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-contianer-menu-opcion-activa gmcompvs-contianer-menu-opcion-js", "id"=>"gmcompvs-contianer-menu-opcion-inicio"));
                    $html.= html_writer::nonempty_tag('h3', 'Inicio',array());
                $html.= html_writer::end_tag('div');
                $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-contianer-menu-opcion gmcompvs-contianer-menu-opcion-js", "id"=>"gmcompvs-contianer-menu-opcion-scores"));
                    $html.= html_writer::nonempty_tag('h3', 'Tabla posiciones',array());
                $html.= html_writer::end_tag('div');
                $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-contianer-menu-opcion gmcompvs-contianer-menu-opcion-js", "id"=>"gmcompvs-contianer-menu-opcion-historial"));
                    $html.= html_writer::nonempty_tag('h3', 'Historial',array());
                $html.= html_writer::end_tag('div');
            $html.= html_writer::end_tag('div');
            $html.= html_writer::start_tag('div', array("id"=>"gmcompvs-activity-container"));
                $html.= html_writer::start_tag('div', array("id"=>"gmcompvs-contianer-menu-opcion-inicio-container", "class"=>"gmcompvs-section-contianer-menu-opcion"));
                    $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container"));
                        $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-victorias"));
                            $html.= html_writer::empty_tag('img', array("src"=>"pix/trophy.png", "class"=>"gmcompvs-trohpy-image"));
                            $html.= html_writer::nonempty_tag('h1', "<b>Número victorias:</b> ",array());
                            $html.= html_writer::nonempty_tag('h1', $numVictorias, array("class" =>"gmcompvs-victories"));
                        $html.= html_writer::end_tag('div');
                    $html.= html_writer::end_tag('div');
                    $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container"));
                        $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-half-container"));
                            $html.= html_writer::nonempty_tag('h2', "Compa&ntilde;eros del curso:", array("class" =>"gmcompvs-titulo"));
                            $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-adversaries gmcompvs-container-overflow-half-height"));
                                $html.= html_writer::nonempty_tag('p','Buscar por nombre:', array("class"=>"gmcompvs-buscador-titulo"));
                                $html.= html_writer::empty_tag('input', array("name"=>"rivalid", "type"=>"text", "placeholder"=>"Nombres", "class"=>"gmcompvs-buscador", "id"=>"gmcompvs-buscador"));
                                foreach($posiblesRivales as $rival)
                                {
                                    $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-rival"));
                                    $html.= html_writer::start_tag('form',
                                        array('action' => new moodle_url('/mod/gmcompvs/attempt.php',
                                            array('userid' => $moodleUserId, 'gmuserid' => $userid ,'instance' => $gmcompvs->id, 'id' => $id)), 'method' => 'post',
                                            'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                                            'id' => 'responseform'));
                                    $html.= html_writer::empty_tag('input', array("name"=>"rivalid", "type"=>"hidden", "value"=>$rival->userid));
                                    $seleccionado = $this->obtener_objetos_dependiendo_usuario($rival->userid);
                                    $html .= html_writer::empty_tag('img', array( "src"=>new moodle_url('/local/gmtienda/'.$seleccionado[0]['valor']), "class"=> 'gmcompvs-profile-image '.$seleccionado[1]['valor'].' '.$seleccionado[2]['valor']));
                                    $html.= html_writer::nonempty_tag('p', "Nombre: <br>".$rival->firstname . ' '. $rival->lastname, array("class"=>"gmcompvs-al-desafiar-nombre"));
                                            if($apuestasActivas == 1)
                                                {
                                                    $html.= $apuestaMonedas;
                                                }
                                            $html.= html_writer::empty_tag('input', array("class" =>"btn btn-primary gmcompvs-end-button-volver",
                                                'type' => 'submit', 'name' => 'next', 'value' => 'Desafiar'));

                                    $html.= html_writer::end_tag('form');
                                    $html.= html_writer::end_tag('div');

                                }
                                if(empty($posiblesRivales) == 1)
                                    {
                                        $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-rival"));
                                            $html.= html_writer::nonempty_tag('p', "No hay estudiantes registrados", array());
                                        $html.= html_writer::end_tag('div');
                                    }
                                $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-rival-offset", "id"=>"gmdl-container-sin-resultados"));
                                    $html.= html_writer::nonempty_tag('p', "No se encontraron estudiantes", array());
                                $html.= html_writer::end_tag('div');
                            $html.= html_writer::end_tag('div');
                        $html.= html_writer::end_tag('div');
                        $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-half-container"));
                            $html.= html_writer::nonempty_tag('h2', "Desafíos pendientes:", array("class" =>"gmcompvs-titulo"));
                            $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-challenges gmcompvs-container-overflow-half-height"));
                                foreach($desafiosPorTerminar as $desafio)
                                    {
                                        $html.= html_writer::start_tag('form',
                                            array('action' => new moodle_url('/mod/gmcompvs/attempt.php',
                                                array('userid' => $moodleUserId, 'gmuserid' => $userid ,'instance' => $gmcompvs->id, 'id' => $id)), 'method' => 'post',
                                                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                                                'id' => 'responseform'));
                                            $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-desafio"));
                                                $html.= html_writer::empty_tag('input', array("name"=>"participacionid", "type"=>"hidden", "value"=>$desafio->participacionid));
                                                $seleccionado = $this->obtener_objetos_dependiendo_usuario($desafio->gmuserid);
                                                $html .= html_writer::empty_tag('img', array( "src"=>new moodle_url('/local/gmtienda/'.$seleccionado[0]['valor']), "class"=> 'gmcompvs-profile-image '.$seleccionado[1]['valor'].' '.$seleccionado[2]['valor']));
                                                $html.= html_writer::nonempty_tag('p', $desafio->firstname . ' '. $desafio->lastname, array("class"=>"gmcompvs-al-desafiar-nombre"));
                                                if($apuestasActivas == 1)
                                                    {
                                                        $html.= $apuestaMonedas;
                                                    }
                                                $html.= html_writer::empty_tag('input', array("class" =>"btn btn-primary gmcompvs-end-button-volver",
                                                'type' => 'submit', 'name' => 'next', 'value' => 'Aceptar desafío'));
                                            $html.= html_writer::end_tag('div');
                                        $html.= html_writer::end_tag('form');
                                    }
                                if(empty($desafiosPorTerminar) == 1)
                                    {
                                        $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-desafio"));
                                            $html.= html_writer::nonempty_tag('p', "No hay desafíos pendientes", array());
                                        $html.= html_writer::end_tag('div');
                                    }
                            $html.= html_writer::end_tag('div');
                        $html.= html_writer::end_tag('div');
                    $html.= html_writer::end_tag('div');
                $html.= html_writer::end_tag('div');
            return $html.$this->render_scores_page($gmcompvs, $userid, $apuestasActivas);
        }



    public function render_scores_page($gmcompvs, $userid, $apuestasActivas)
        {
            $filas = $this->obtener_victorias_usuarios($gmcompvs->id);
            $html= html_writer::start_tag('div', array("id"=>"gmcompvs-contianer-menu-opcion-scores-container", "class"=>"gmcompvs-section-contianer-menu-opcion"));
                $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-scores-scroll"));
                    $html.= html_writer::start_tag('table', array("class"=>"gmcompvs-table", "id"=>"gmcompvs-table-scores"));
                        $html.= html_writer::start_tag('thead', array("class"=>"gmcompvs-thead"));
                            $html.= html_writer::start_tag('tr', array());
                                $html.= html_writer::nonempty_tag('th', "Posici&oacute;n", array("class"=>"gmcompvs-table-posicion-column"));
                                $html.= html_writer::nonempty_tag('th', "Nombre", array("class"=>"gmcompvs-table-name-column"));
                                $html.= html_writer::nonempty_tag('th', "N&uacute;m. victorias", array("class"=>"gmcompvs-table-victorias-column"));
                            $html.= html_writer::end_tag('tr', array());
                        $html.= html_writer::end_tag('thead', array());
                        $html.= html_writer::start_tag('tbody', array("class"=>"gmcompvs-tbody"));
                            $ultimosPuntos = 0;
                            $primeraFila = 1;
                            $lugar = 1;
                            foreach($filas as $fila)
                                {
                                    $html.= html_writer::start_tag('tr', array());
                                        if($ultimosPuntos == $fila->victorias && $primeraFila==0)
                                            {
                                                $lugar--;
                                            }
                                        /*Manera de acceder a pix/ de otro plugin:
                                         * new moodle_url('/local/gmtienda/pix/icon.png')
                                         * */
                                        $html.= html_writer::start_tag('td', array("class"=>"gmcompvs-table-posicion-column"));
                                            if($lugar == 1){ $html.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmcompvs-trohpy-image")); }
                                            else if($lugar == 2){ $html.= html_writer::empty_tag('img', array("src"=>"pix/trophy_second.png", "class"=>"gmcompvs-trohpy-image")); }
                                            else if($lugar == 3){ $html.= html_writer::empty_tag('img', array("src"=>"pix/trophy_third.png", "class"=>"gmcompvs-trohpy-image")); }
                                            else{ $html.= html_writer::nonempty_tag('p',$lugar.'°' ,array("")); }
                                        $html.= html_writer::end_tag('td', array());
                                        $html.= html_writer::start_tag('td',array("class"=>"gmcompvs-table-name-column"));
                                        $seleccionado = $this->obtener_objetos_dependiendo_usuario($fila->gmuserid);
                                        $html .= html_writer::start_tag('div', array("class"=>"gmtienda-muestra-en-tabla"));
                                        $html.= html_writer::empty_tag('img', array( "src"=>new moodle_url('/local/gmtienda/'.$seleccionado[0]['valor']), "class"=> 'gmcompvs-profile-image '.$seleccionado[1]['valor'].' '.$seleccionado[2]['valor']));
                                        $html.= html_writer::nonempty_tag('p',$fila->firstname.' '.$fila->lastname.'  ('.$fila->username.')' ,array(""));
                                        $html.= html_writer::end_tag('div');
                                        $html.= html_writer::end_tag('td');
                                        $html.= html_writer::nonempty_tag('td', $fila->victorias , array("class"=>"gmcompvs-table-victorias-column"));
                                        if($primeraFila == 1)
                                            {
                                                $primeraFila = 0;
                                            }
                                        $ultimosPuntos = $fila->victorias;
                                    $lugar++;
                                    $html.= html_writer::end_tag('tr', array());
                                }
                            if($primeraFila == 1)
                                {
                                    $html.= html_writer::start_tag('tr', array());
                                        $html.= html_writer::start_tag('td', array("class"=>"gmcompvs-table-posicion-column"));
                                        $html.= html_writer::end_tag('td', array());
                                        $html.= html_writer::nonempty_tag('td', 'Aun no hay competencias registradas' , array("class"=>"gmcompvs-table-name-column"));
                                        $html.= html_writer::nonempty_tag('td', '0' , array("class"=>"gmcompvs-table-victorias-column"));
                                    $html.= html_writer::end_tag('tr', array());
                                }
                        $html.= html_writer::end_tag('tbody', array());
                    $html.= html_writer::end_tag('table', array());
                $html.= html_writer::end_tag('div');
            $html.= html_writer::end_tag('div');
            return $html.$this->render_attempt_page($gmcompvs, $userid, $apuestasActivas);
        }
    public function render_attempt_page($gmcompvs, $userid, $apuestasActivas)
        {
            $partidas = $this->obtener_historial_usuario($gmcompvs->id, $userid);
            $empates = 0;
            $victorias = 0;
            $derrotas = 0;
            $encurso = 0;
            $retiradas = 0;
            $contenidoTabla = '';
            foreach($partidas as $partida)
                {
                    $tiempoFinal = " - - - ";
                    $tiempoFinalContrincante = " - - - ";
                    $imagen = "pix/derrota.png";
                    if(is_null($partida->fecha_fin_a))
                        {
                            $tiempoFinal = date('Y-m-d', $partida->fecha_fin_a);
                            $imagen = "pix/retirada.png";
                            $retiradas++;
                        }
                    else if(is_null($partida->fecha_inicio_b))
                        {
                            $imagen = "pix/tregua.png";
                            $encurso++;
                        }
                    else if(is_null($partida->fecha_fin_b))
                        {
                            $imagen = "pix/tregua.png";
                            $encurso++;
                        }
                    else if($partida->puntuacion_a > $partida->puntuacion_b)
                        {
                            if($imagen=='pix/derrota.png')
                                {
                                    $imagen = "pix/victoria.png";
                                    $victorias++;
                                }
                        }
                    else if($partida->puntuacion_a == $partida->puntuacion_b)
                        {
                            if($imagen=='pix/derrota.png')
                                {
                                    $imagen = "pix/empate.png";
                                    $empates++;
                                }
                        }
                    else
                        {
                            $derrotas++;
                        }
                        $contenidoTabla.= html_writer::start_tag('tr', array());
                        $contenidoTabla .= html_writer::start_tag('td',array("class"=>"gmcompvs-table-history-name"));
                        $contenidoTabla .= html_writer::start_tag('div', array("class"=>"gmtienda-muestra-en-tabla"));
                        $seleccionado = $this->obtener_objetos_dependiendo_usuario($partida->gmuserid_b);
                        $contenidoTabla .= html_writer::empty_tag('img', array( "src"=>new moodle_url('/local/gmtienda/'.$seleccionado[0]['valor']), "class"=> 'gmcompvs-profile-image '.$seleccionado[1]['valor'].' '.$seleccionado[2]['valor']));
                        $contenidoTabla .= html_writer::nonempty_tag('p',$partida->firstname_b.' '.$partida->lastname_b.' <br> ('.$partida->firstname_b.')' ,array(""));
                        $contenidoTabla .= html_writer::end_tag('td');
                        $contenidoTabla.= html_writer::end_tag('div');
                        $contenidoTabla.= html_writer::nonempty_tag('td', $partida->puntuacion_a, array("class"=>"gmcompvs-table-history-points"));
                        if($apuestasActivas == 1)
                            {
                                if(is_null($partida->monedas_a))
                                    {
                                        $contenidoTabla.= html_writer::nonempty_tag('td', '0', array("class"=>"gmcompvs-table-history-points"));
                                    }
                                else
                                    {
                                        $contenidoTabla.= html_writer::nonempty_tag('td', ' '.$partida->monedas_a, array("class"=>"gmcompvs-table-history-points"));
                                    }

                            }
                        $contenidoTabla.= html_writer::nonempty_tag('td', $partida->puntuacion_b, array("class"=>"gmcompvs-table-history-points"));
                        if($apuestasActivas == 1)
                            {
                                if(is_null($partida->monedas_b))
                                    {
                                        $contenidoTabla.= html_writer::nonempty_tag('td', '0', array("class"=>"gmcompvs-table-history-points"));
                                    }
                                else
                                    {
                                        $contenidoTabla.= html_writer::nonempty_tag('td', ' '.$partida->monedas_b, array("class"=>"gmcompvs-table-history-points"));
                                    }
                            }
                        $contenidoTabla.= html_writer::start_tag('td', array("class"=>"gmcompvs-table-history-state"));
                            $contenidoTabla.= html_writer::empty_tag('img', array("src"=>$imagen, "class"=>"gmcompvs-trohpy-image-table"));
                        $contenidoTabla.= html_writer::end_tag('td', array());
                    $contenidoTabla.= html_writer::end_tag('tr', array());

                }

            $html= html_writer::tag('div', '',array("class" =>"gmcompvs-linea"));
            $html.= html_writer::start_tag('div', array("id"=>"gmcompvs-contianer-menu-opcion-historial-container", "class"=>"gmcompvs-section-contianer-menu-opcion"));
            $html.= html_writer::start_tag('div', array("id"=>"gmcompvs-fieldset-div"));
                $html.= html_writer::start_tag('fieldset', array("id"=>"gmcompvs-fieldset"));
                    $html.= html_writer::nonempty_tag('legend', 'Resumen de los estados de los desafíos', array());
                    $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-resume"));
                        $html.= html_writer::start_tag('div', array());
                            $html.= html_writer::empty_tag('img', array("src"=>"pix/victoria.png", "class"=>"gmcompvs-trohpy-image-table"));
                            $html.= html_writer::nonempty_tag('h3', 'Victorias: '.$victorias, array());
                        $html.= html_writer::end_tag('div');
                        $html.= html_writer::start_tag('div', array());
                            $html.= html_writer::empty_tag('img', array("src"=>"pix/empate.png", "class"=>"gmcompvs-trohpy-image-table"));
                            $html.= html_writer::nonempty_tag('h3', 'Empates: '.$empates, array());
                        $html.= html_writer::end_tag('div');
                        $html.= html_writer::start_tag('div', array());
                            $html.= html_writer::empty_tag('img', array("src"=>"pix/derrota.png", "class"=>"gmcompvs-trohpy-image-table"));
                            $html.= html_writer::nonempty_tag('h3', 'Derrotas: '.$derrotas, array());
                        $html.= html_writer::end_tag('div');
                    $html.= html_writer::end_tag('div');
                    $html.= html_writer::start_tag('div', array("class"=>"gmcompvs-container-resume"));
                        $html.= html_writer::start_tag('div', array());
                            $html.= html_writer::empty_tag('img', array("src"=>"pix/tregua.png", "class"=>"gmcompvs-trohpy-image-table"));
                            $html.= html_writer::nonempty_tag('h3', 'En curso: '.$encurso, array());
                        $html.= html_writer::end_tag('div');
                        $html.= html_writer::start_tag('div', array());
                            $html.= html_writer::empty_tag('img', array("src"=>"pix/retirada.png", "class"=>"gmcompvs-trohpy-image-table"));
                            $html.= html_writer::nonempty_tag('h3', 'Retirada: '.$retiradas, array());
                        $html.= html_writer::end_tag('div');
                    $html.= html_writer::end_tag('div');
                $html.= html_writer::end_tag('fieldset');
                $html.= html_writer::end_tag('div');
                    $html.= html_writer::start_tag('div', array( "id"=>"gmcompvs-table-historial-container"));
                        $html.= html_writer::start_tag('table', array("class"=>"gmcompvs-table", "id"=>"gmcompvs-table-historial"));
                            $html.= html_writer::start_tag('thead', array("class"=>"gmcompvs-thead"));
                                $html.= html_writer::start_tag('tr', array());
                                    $html.= html_writer::nonempty_tag('th', "Nombre contrincante", array("class"=>"gmcompvs-table-history-name"));
                                    $html.= html_writer::nonempty_tag('th', "Tus puntos", array("class"=>"gmcompvs-table-history-points"));
                                    if($apuestasActivas == 1)
                                        {
                                            $html.= html_writer::nonempty_tag('th', "Tus monedas apostadas", array("class"=>"gmcompvs-table-history-points"));
                                        }
                                    $html.= html_writer::nonempty_tag('th', "Puntos contrincante", array("class"=>"gmcompvs-table-history-points"));
                                    if($apuestasActivas == 1)
                                        {
                                            $html.= html_writer::nonempty_tag('th', "Monedas apostadas contrincante", array("class"=>"gmcompvs-table-history-points"));
                                        }
                                    $html.= html_writer::nonempty_tag('th', "Estado de desaf&iacute;o", array("class"=>"gmcompvs-table-history-state"));
                                $html.= html_writer::end_tag('tr', array());
                            $html.= html_writer::end_tag('thead', array());
                            $html.= html_writer::start_tag('tbody', array("class"=>"gmcompvs-tbody"));
                                $html.= $contenidoTabla;
                                $aux = $victorias + $empates + $derrotas + $retiradas + $encurso;
                                if($aux == 0)
                                    {
                                        $imagen = "pix/paz.png";
                                        $html.= html_writer::start_tag('tr', array());
                                            $html.= html_writer::nonempty_tag('td', 'No has terminado ningun combate (Pacifista)', array("class"=>"gmcompvs-table-history-name"));
                                            $html.= html_writer::nonempty_tag('td', ' 0 ', array("class"=>"gmcompvs-table-history-points"));
                                            if($apuestasActivas == 1)
                                                {
                                                    $html.= html_writer::nonempty_tag('td', ' 0 ', array("class"=>"gmcompvs-table-history-points"));
                                                }
                                            $html.= html_writer::nonempty_tag('td', ' 0 ', array("class"=>"gmcompvs-table-history-points"));
                                            if($apuestasActivas == 1)
                                                {
                                                    $html.= html_writer::nonempty_tag('td', ' 0 ', array("class"=>"gmcompvs-table-history-points"));
                                                }
                                            $html.= html_writer::start_tag('td', array("class"=>"gmcompvs-table-history-state"));
                                                $html.= html_writer::empty_tag('img', array("src"=>$imagen, "class"=>"gmcompvs-trohpy-image-table"));
                                            $html.= html_writer::end_tag('td', array());
                                        $html.= html_writer::end_tag('tr', array());
                                    }
                            $html.= html_writer::end_tag('tbody');
                        $html.= html_writer::end_tag('table');
                    $html.= html_writer::end_tag('div');
            $html.= html_writer::end_tag('div');
            $html.= html_writer::end_tag('div');
            return $html;
        }



    private function obtener_dato_sql_victorias($instancia, $usuario)
        {
            global $DB;
            $sql = "";
            $sql.=" SELECT COUNT(*) FROM";
            $sql.=" (SELECT {gmdl_partida}.id as partidaid, {gmdl_participacion}.puntuacion as puntuacion";
            $sql.=" FROM {gmdl_partida} ";
            $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" WHERE {gmdl_participacion}.gmdl_usuario_id = $usuario AND";
            $sql.=" {gmdl_participacion}.fecha_inicio IS NOT NUll AND";
            $sql.=" {gmdl_participacion}.fecha_fin IS NOT NUll AND";
            $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia) as a";
            $sql.=" JOIN ";
            $sql.=" (SELECT {gmdl_partida}.id as partidaid, {gmdl_participacion}.puntuacion  as puntuacion";
            $sql.=" FROM {gmdl_partida}";
            $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" WHERE {gmdl_participacion}.gmdl_usuario_id != $usuario AND";
            $sql.=" {gmdl_participacion}.fecha_inicio IS NOT NUll AND";
            $sql.=" {gmdl_participacion}.fecha_fin IS NOT NUll AND";
            $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia) as b";
            $sql.=" ON a.partidaid = b.partidaid";
            $sql.=" WHERE a.puntuacion > b.puntuacion";
            $res = $DB->count_records_sql($sql, null, $limitfrom = 0, $limitnum = 0);
            return $res;
        }

    private function obtener_posibles_rivales($instancia, $usuario)
        {
            global $DB;
            $sql ="";
            $sql.=" SELECT {gmdl_usuario}.id as userid, username, firstname, lastname from";
            $sql.=" {user} JOIN";
            $sql.=" {role_assignments} ON {user}.id = {role_assignments}.userid ";
            $sql.=" JOIN {role} ON {role_assignments}.roleid = {role}.id";
            $sql.=" JOIN {context} ON {role_assignments}.contextid = {context}.id";
            $sql.=" JOIN {course} ON {context}.instanceid =  {course}.id";
            $sql.=" JOIN {gmcompvs} ON {gmcompvs}.course = {course}.id";
            $sql.=" JOIN {gmdl_usuario} ON {user}.id = {gmdl_usuario}.mdl_id_usuario";
            $sql.=" WHERE {context}.contextlevel = 50 AND ";
            $sql.=" {gmcompvs}.id = $instancia AND ";
            $sql.=" {role}.id = 5 AND";
            $sql.=" {gmdl_usuario}.id != $usuario AND" ;
            $sql.=" {gmdl_usuario}.id not in (";
            $sql.=" SELECT contrincante FROM";
            $sql.=" (SELECT {gmdl_partida}.id as partidaid, {gmdl_participacion}.gmdl_usuario_id as principal, fecha_inicio";
            $sql.=" FROM {gmdl_partida} ";
            $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" WHERE {gmdl_participacion}.gmdl_usuario_id = $usuario AND";
            $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia ) as a";
            $sql.=" JOIN ";
            $sql.=" (SELECT {gmdl_partida}.id as partidaid, {gmdl_participacion}.gmdl_usuario_id as contrincante, fecha_inicio";
            $sql.=" FROM {gmdl_partida} ";
            $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" WHERE {gmdl_participacion}.gmdl_usuario_id != $usuario AND";
            $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia ) as b";
            $sql.=" ON a.partidaid = b.partidaid";
            $sql.=" WHERE a.fecha_inicio IS NULL OR";
            $sql.=" b.fecha_inicio IS NULL)";
            return $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);

        }
    private function obtener_desafios_pendientes($instancia, $usuario)
        {
            global $DB;
            $sql ="";
            $sql.=" SELECT";
            $sql.=" a.participacionid,";
            $sql.=" username,";
            $sql.=" firstname,";
            $sql.=" lastname,";
            $sql.=" {gmdl_usuario}.id as gmuserid";
            $sql.=" FROM";
            $sql.=" {user} JOIN";
            $sql.=" {gmdl_usuario} ON {gmdl_usuario}.mdl_id_usuario = {user}.id";
            $sql.=" JOIN";
            $sql.=" (SELECT {gmdl_partida}.id as partidaid, {gmdl_participacion}.gmdl_usuario_id as contrincante";
            $sql.=" FROM {gmdl_partida} ";
            $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" WHERE gmdl_comp_vs_id = ".$instancia." AND";
            $sql.=" {gmdl_participacion}.gmdl_usuario_id != ".$usuario." AND";
            $sql.=" {gmdl_participacion}.fecha_inicio IS NOT NUll) as b";
            $sql.=" ON b.contrincante = {gmdl_usuario}.id";
            $sql.=" JOIN (SELECT {gmdl_partida}.id as partidaid, {gmdl_participacion}.id as participacionid, {gmdl_participacion}.gmdl_usuario_id as principal";
            $sql.=" FROM {gmdl_participacion}";
            $sql.=" JOIN {gmdl_partida} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" WHERE {gmdl_partida}.gmdl_comp_vs_id = ".$instancia." AND";
            $sql.=" {gmdl_participacion}.gmdl_usuario_id = ".$usuario." AND";
            $sql.=" {gmdl_participacion}.fecha_inicio IS NUll) as a";
            $sql.=" ON a.partidaid = b.partidaid";
            return $DB->get_records_sql($sql, null, $limitfrom = 0, $limitnum = 0);
        }

    private function obtener_victorias_usuarios($instancia)
        {

            global $DB;
            $sql = "SELECT username, firstname, lastname, c.victorias, {gmdl_usuario}.id as gmuserid FROM";
            $sql.= " {user} JOIN";
            $sql.= " {gmdl_usuario} ON  {user}.id = {gmdl_usuario}.mdl_id_usuario";
            $sql.= " JOIN ";
            $sql.=" (SELECT a.gmdl_usuario_id as gmdluserid, COUNT(*) as victorias FROM";
            $sql.=" (SELECT {gmdl_partida}.id as partidaid, {gmdl_participacion}.puntuacion as puntuacion, gmdl_usuario_id";
            $sql.=" FROM {gmdl_partida} ";
            $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" WHERE ";
            $sql.=" {gmdl_participacion}.fecha_inicio IS NOT NUll AND";
            $sql.=" {gmdl_participacion}.fecha_fin IS NOT NUll AND";
            $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia) as a";
            $sql.=" JOIN ";
            $sql.=" (SELECT {gmdl_partida}.id as partidaid, {gmdl_participacion}.puntuacion  as puntuacion, gmdl_usuario_id";
            $sql.=" FROM {gmdl_partida}";
            $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" WHERE ";
            $sql.=" {gmdl_participacion}.fecha_inicio IS NOT NUll AND";
            $sql.=" {gmdl_participacion}.fecha_fin IS NOT NUll AND";
            $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia) as b";
            $sql.=" ON a.partidaid = b.partidaid";
            $sql.=" WHERE a.puntuacion > b.puntuacion";
            $sql.=" AND a.gmdl_usuario_id != b.gmdl_usuario_id";
            $sql.=" GROUP BY a.gmdl_usuario_id) as c";
            $sql.=" ON c.gmdluserid = {gmdl_usuario}.id";
            $sql.=" ORDER BY victorias DESC";
            return $DB->get_records_sql($sql, null, $limitfrom = 0, $limitnum = 0);
        }

    private function obtener_historial_usuario($instancia, $usuario)
        {

            global $DB;
            $sql=" SELECT a.*, b.* FROM";
            $sql.=" (SELECT ";
            $sql.="         username as username_a,";
            $sql.="         firstname as firstname_a,";
            $sql.="         lastname as lastname_a,";
            $sql.="         {gmdl_partida}.id as partidaid_a,";
            $sql.="         {gmdl_participacion}.fecha_inicio as fecha_inicio_a,";
            $sql.="         {gmdl_participacion}.fecha_fin as fecha_fin_a,";
            $sql.="         {gmdl_participacion}.puntuacion as puntuacion_a,";
            $sql.="         {gmdl_participacion}.gmdl_usuario_id as gmuserid_a,";
            $sql.="         {gmdl_apuesta}.monedas_plata as monedas_a";
            $sql.=" FROM {gmdl_partida} ";
            $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" JOIN {gmdl_usuario} ON {gmdl_participacion}.gmdl_usuario_id = {gmdl_usuario}.id";
            $sql.=" JOIN {user} ON {gmdl_usuario}.mdl_id_usuario = {user}.id";
            $sql.=" LEFT JOIN {gmdl_apuesta} ON {gmdl_participacion}.id = {gmdl_apuesta}.gmdl_participacion_id";
            $sql.=" WHERE {gmdl_participacion}.gmdl_usuario_id = $usuario AND";
            $sql.=" {gmdl_participacion}.fecha_inicio IS NOT NUll AND";
            $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia) as a";
            $sql.=" JOIN ";
            $sql.=" (SELECT ";
            $sql.="         username as username_b,";
            $sql.="         firstname as firstname_b,";
            $sql.="         lastname as lastname_b,";
            $sql.="         {gmdl_partida}.id as partidaid_b,";
            $sql.="         {gmdl_participacion}.fecha_inicio as fecha_inicio_b,";
            $sql.="         {gmdl_participacion}.fecha_fin as fecha_fin_b,";
            $sql.="         {gmdl_participacion}.puntuacion as puntuacion_b,";
            $sql.="         {gmdl_participacion}.gmdl_usuario_id as gmuserid_b,";
            $sql.="         {gmdl_apuesta}.monedas_plata as monedas_b";
            $sql.=" FROM {gmdl_partida}";
            $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
            $sql.=" JOIN {gmdl_usuario} ON {gmdl_participacion}.gmdl_usuario_id = {gmdl_usuario}.id";
            $sql.=" JOIN {user} ON {gmdl_usuario}.mdl_id_usuario = {user}.id";
            $sql.=" LEFT JOIN {gmdl_apuesta} ON {gmdl_participacion}.id = {gmdl_apuesta}.gmdl_participacion_id";
            $sql.=" WHERE {gmdl_participacion}.gmdl_usuario_id != $usuario AND";
            $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia) as b";
            $sql.=" ON a.partidaid_a = b.partidaid_b";
            return $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);
        }

    private function obtener_partidas_enprogreso($instancia, $usuario)
    {

        global $DB;
        $sql=" SELECT a.*, b.* FROM";
        $sql.=" (SELECT ";
        $sql.="         username as username_a,";
        $sql.="         firstname as firstname_a,";
        $sql.="         lastname as lastname_a,";
        $sql.="         {gmdl_partida}.id as partidaid_a,";
        $sql.="         {gmdl_participacion}.fecha_inicio as fecha_inicio_a,";
        $sql.="         {gmdl_participacion}.fecha_fin as fecha_fin_a,";
        $sql.="         {gmdl_participacion}.puntuacion as puntuacion_a,";
        $sql.="         {gmdl_participacion}.id as participacionid_a,";
        $sql.="         {gmdl_participacion}.gmdl_usuario_id as gmdlusuarioid_a";
        $sql.=" FROM {gmdl_partida} ";
        $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
        $sql.=" JOIN {gmdl_usuario} ON {gmdl_participacion}.gmdl_usuario_id = {gmdl_usuario}.id";
        $sql.=" JOIN {user} ON {gmdl_usuario}.mdl_id_usuario = {user}.id";
        $sql.=" WHERE {gmdl_participacion}.gmdl_usuario_id = $usuario AND";
        $sql.=" {gmdl_participacion}.fecha_inicio IS NOT NUll AND";
        $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia) as a";
        $sql.=" JOIN ";
        $sql.=" (SELECT ";
        $sql.="         username as username_b,";
        $sql.="         firstname as firstname_b,";
        $sql.="         lastname as lastname_b,";
        $sql.="         {gmdl_partida}.id as partidaid_b,";
        $sql.="         {gmdl_participacion}.fecha_inicio as fecha_inicio_b,";
        $sql.="         {gmdl_participacion}.fecha_fin as fecha_fin_b,";
        $sql.="         {gmdl_participacion}.puntuacion as puntuacion_b,";
        $sql.="         {gmdl_participacion}.id as participacionid_b,";
        $sql.="         {gmdl_participacion}.gmdl_usuario_id as gmdlusuarioid_b";
        $sql.=" FROM {gmdl_partida}";
        $sql.=" JOIN {gmdl_participacion} ON {gmdl_partida}.id = {gmdl_participacion}.gmdl_partida_id";
        $sql.=" JOIN {gmdl_usuario} ON {gmdl_participacion}.gmdl_usuario_id = {gmdl_usuario}.id";
        $sql.=" JOIN {user} ON {gmdl_usuario}.mdl_id_usuario = {user}.id";
        $sql.=" WHERE {gmdl_participacion}.gmdl_usuario_id != $usuario AND";
        $sql.=" {gmdl_participacion}.fecha_inicio IS NOT NUll AND";
        $sql.=" {gmdl_partida}.gmdl_comp_vs_id = $instancia) as b";
        $sql.=" ON a.partidaid_a = b.partidaid_b";
        $sql.=" WHERE b.fecha_fin_b IS NUll OR";
        $sql.=" a.fecha_fin_a IS NUll";
        return $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);
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


    private function insertar_usuario($usuario)
        {
            global $DB;
            $data = new stdClass();
            $data->mdl_id_usuario = $usuario;
            $data->nivel_actual = 1;
            $data->experiencia_actual = 0;
            return $DB->insert_record('gmdl_usuario', $data, $returnid=true, $bulk=false);
        }

    private function obtener_monedas_usuario($usuario)
        {
            global $DB;
            return $DB->get_record('gmdl_usuario', array("id"=>$usuario), $fields='*', $strictness=IGNORE_MISSING)->monedas_plata;
        }

    private function obtener_apuestas_activas_instancia($instancia)
        {
            global $DB;
            return $DB->get_record('gmcompvs', array("id"=>$instancia), $fields='*', $strictness=IGNORE_MISSING)->apuestas_activas;
        }

        /*
         *
         * No funciona correctamente la funcion, si actualiza las fecha fin pero los eventos no los está lanzando....
         *
         * */

    private function revisar_partidas_enprogreso( $instancia, $usuario ){
        /*global $OUTPUT;*/

        global $DB;
        $tiempoactual = time();
        $partidas = $this->obtener_partidas_enprogreso( $instancia, $usuario);

        foreach ( $partidas as $partida ){
            $cambiorealizado = false;
            if( is_null($partida->fecha_fin_a) && ($tiempoactual-$partida->fecha_inicio_a) >= 86400 ){
                $values = (object)[
                    'id' => $partida->participacionid_a,
                    'fecha_fin' => $tiempoactual
                ];
                $partida->fecha_fin_a = $tiempoactual;
                $DB->update_record('gmdl_participacion',$values);
                $cambiorealizado = true;
            }

            if( is_null($partida->fecha_fin_b) && ($tiempoactual-$partida->fecha_inicio_b) >= 86400 ){
                $values = (object)[
                    'id' => $partida->participacionid_b,
                    'fecha_fin' => $tiempoactual
                ];
                $partida->fecha_fin_b = $tiempoactual;
                $DB->update_record('gmdl_participacion',$values);
                $cambiorealizado = true;
            }

            if( $cambiorealizado && !is_null($partida->fecha_fin_a) && !is_null($partida->fecha_fin_b)){
                /*$notify = new \core\output\notification('cambiosrealizado',
                    \core\output\notification::NOTIFY_SUCCESS);

                echo $OUTPUT->render($notify);*/
                $gmcompvs  = $DB->get_record('gmcompvs', array('id' => $instancia), '*', MUST_EXIST);
                $course     = $DB->get_record('course', array('id' => $gmcompvs->course), '*', MUST_EXIST);
                $cm         = get_coursemodule_from_instance('gmcompvs', $gmcompvs->id, $course->id, false, MUST_EXIST);
                $context = context_module::instance($cm->id);

                $apuestacontrincanteexiste = false;
                $apuestacontrincante = null;
                try{
                    $apuestacontrincante = $DB->get_record('gmdl_apuesta', array("gmdl_participacion_id" => $partida->participacionid_b), '*', MUST_EXIST);
                    $apuestacontrincanteexiste = true;
                } catch (dml_exception $e) {
                    $apuestacontrincanteexiste = false;
                }

                $apuestausuarioexiste = false;
                $apuestausuario = null;
                try {
                    $apuestausuario = $DB->get_record('gmdl_apuesta', array("gmdl_participacion_id" => $partida->participacionid_a), '*', MUST_EXIST);
                    $apuestausuarioexiste = true;
                } catch (dml_exception $e) {
                    $apuestausuarioexiste = false;
                }

                $tiempotardado = $partida->fecha_fin_a - $partida->fecha_inicio_a;
                $tiempotardadoContri = $partida->fecha_fin_b - $partida->fecha_inicio_b;
                $participacionid = $partida->participacionid_a;

                if ($tiempotardado > $tiempotardadoContri) {
                    $participacionid = $partida->participacionid_b;
                    $partida->puntuacion_b += (int)($partida->puntuacion_b / 5);
                    $userScoreUpdate = $partida->puntuacion_b;
                } else {
                    $partida->puntuacion_a += (int)($partida->puntuacion_a / 5);
                    $userScoreUpdate = $partida->puntuacion_a;
                }

                $values = (object)[
                    'id' => $participacionid,
                    'puntuacion' => $userScoreUpdate
                ];

                $DB->update_record('gmdl_participacion', $values);
                $userid = $usuario;
                if( $partida->puntuacion_a > $partida->puntuacion_b )
                    $userid = $partida->gmdlusuarioid_a;
                else
                    $userid = $partida->gmdlusuarioid_b;

                $monedasadar = 0;
                $useridmonedas = -1;
                if( $apuestacontrincanteexiste || $apuestausuarioexiste ){
                    if( $apuestacontrincanteexiste && $apuestausuarioexiste ){

                        $monedasadar = $apuestacontrincante->monedas_plata + $apuestausuario->monedas_plata;

                    }else{

                        if( $apuestacontrincanteexiste ){

                            $useridmonedas = $partida->gmdlusuarioid_b;
                            $monedasdevolver = $apuestacontrincante->monedas_plata;

                        }else{

                            $useridmonedas = $partida->gmdlusuarioid_a;
                            $monedasdevolver = $apuestausuario->monedas_plata;

                        }

                        $event = \local_gamedlemaster\event\gmcompvs_compFinishedReturnCon::create(array(
                            'objectid' => $gmcompvs->id,
                            'context' => $context,
                            'other' => array('userid' => $useridmonedas, 'monedas' => $monedasdevolver, 'rason' => 'bandera apagada'),
                        ));

                        $event->add_record_snapshot('gmcompvs', $gmcompvs);
                        $event->trigger();

                    }
                }

                $moodleuserid = $DB->get_record('gmdl_usuario',array('id' => $userid));

                $completion = new completion_info($course);
                if($completion->is_enabled($cm) && $gmcompvs->completionnumwon != 0 && $gmcompvs->completionnumwon != NULL ) {
                    $completion->update_state($cm,COMPLETION_COMPLETE,$moodleuserid->mdl_id_usuario);
                }

                if($partida->puntuacion_a != $partida->puntuacion_b){
                    $event = \local_gamedlemaster\event\gmcompvs_compFinishedWon::create(array(
                        'objectid' => $gmcompvs->id,
                        'context' => $context,
                        'other' => array('userid' => $userid, 'monedas' => $monedasadar),
                    ));

                    $event->add_record_snapshot('gmcompvs', $gmcompvs);
                    $event->trigger();

                }else{
                    /*$notify = new \core\output\notification('empate',
                        \core\output\notification::NOTIFY_SUCCESS);

                    echo $OUTPUT->render($notify);*/
                    if( $useridmonedas != $partida->gmdlusuarioid_a && $apuestausuarioexiste){
                        /*$notify = new \core\output\notification('antes de evento '.$partida->gmdlusuarioid_a,
                            \core\output\notification::NOTIFY_SUCCESS);

                        echo $OUTPUT->render($notify);*/
                        $event = \local_gamedlemaster\event\gmcompvs_compFinishedReturnCon::create(array(
                            'objectid' => $gmcompvs->id,
                            'context' => $context,
                            'other' => array('userid' => $partida->gmdlusuarioid_a, 'monedas' => $apuestausuario->monedas_plata, 'rason' => 'juego empatado'),
                        ));

                        $event->add_record_snapshot('gmcompvs', $gmcompvs);
                        $event->trigger();
                        /*$notify = new \core\output\notification('despues de evento '.$partida->gmdlusuarioid_a,
                            \core\output\notification::NOTIFY_SUCCESS);

                        echo $OUTPUT->render($notify);*/
                    }

                    if( $useridmonedas != $partida->gmdlusuarioid_b && $apuestacontrincanteexiste){
                        /*$notify = new \core\output\notification('antes de evento '.$partida->gmdlusuarioid_b,
                            \core\output\notification::NOTIFY_SUCCESS);

                        echo $OUTPUT->render($notify);*/
                        $event = \local_gamedlemaster\event\gmcompvs_compFinishedReturnCon::create(array(
                            'objectid' => $gmcompvs->id,
                            'context' => $context,
                            'other' => array('userid' => $partida->gmdlusuarioid_b, 'monedas' => $apuestacontrincante->monedas_plata, 'rason' => 'juego empatado'),
                        ));

                        $event->add_record_snapshot('gmcompvs', $gmcompvs);
                        $event->trigger();
                        /*$notify = new \core\output\notification('despues de evento '.$partida->gmdlusuarioid_a,
                            \core\output\notification::NOTIFY_SUCCESS);

                        echo $OUTPUT->render($notify);*/
                    }

                }

                if( $apuestausuarioexiste ){

                    $apuestausuario->activa = 0;
                    $DB->update_record('gmdl_apuesta',$apuestausuario);

                }

                if( $apuestacontrincanteexiste ){

                    $apuestacontrincante->activa = 0;
                    $DB->update_record('gmdl_apuesta',$apuestacontrincante);

                }

            }

        }

    }

}
