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
                    array('id' => $quba->get_id(),'cm' => $cm->instance,'userid' => $userid)), 'method' => 'post',
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


    public function render_results_attempt($userScore){

        if($userScore > 5 ){

            $mensaje = 'Felicidades! Pregunta diaria contestada correctamente!';
            $class = 'ganador';

        }else{

            $mensaje = 'Oh no!, fallaste la pregunta diaria ';
            $class = 'perdedor';

        }

        $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";

        $display .= html_writer::start_tag('div');

            $display .= html_writer::start_tag('h2',array('class' => 'gmpregdiarias-titulo-'.$class));

                $display .= html_writer::start_tag('b');

                    $display .= $mensaje;

                $display .= html_writer::end_tag('b');

            $display .= html_writer::end_tag('h2');

        $display .= html_writer::end_tag('div');

        return $display;

    }




    public function render_main_page($gmpregdiarias, $userid,$id)
        {
            $moodleUserId = $userid;
            $userid = $this->obtener_usuario_gamedle($moodleUserId);
            $sepuedecontestar = $this->pregunta_no_contestada_hoy($userid,$gmpregdiarias->id);

            if($sepuedecontestar){

                /*$dificultades = $this->obtener_todas_dificultades();
                $victorias = $this->obtener_cpus_vencidas($gmpregdiarias->id, $userid);
                $compusVencidas = '';
                $nombresDificultades = '';
                $valoresSelect = '';
                foreach($dificultades as $dificultad)
                {
                    $noEncontrado = TRUE;
                    $nombresDificultades.= html_writer::nonempty_tag('th', $dificultad->nombre, array("class"=>"gmpregdiarias-color-cpu-nivel-".$dificultad->id));
                    foreach($victorias as $victoria)
                    {
                        if($victoria->gmdl_dificultad_cpu_id == $dificultad->id && $noEncontrado)
                        {
                            $compusVencidas.= html_writer::start_tag('td', array());
                            $compusVencidas.= html_writer::empty_tag('img', array("src"=>"pix/cpu_vencida.png", "class"=> 'gmpregdiarias-imagen-cpu'));
                            $compusVencidas.= html_writer::end_tag('td', array());
                            $noEncontrado = FALSE;
                        }
                    }
                    if($noEncontrado)
                    {
                        $compusVencidas.= html_writer::start_tag('td', array());
                        $compusVencidas.= html_writer::empty_tag('img', array("src"=>"pix/cpu_activa.png", "class"=> 'gmpregdiarias-imagen-cpu'));
                        $compusVencidas.= html_writer::end_tag('td', array());
                    }


                    $valoresSelect.= html_writer::nonempty_tag('option', $dificultad->nombre, array("value"=> $dificultad->id));
                }


                $html = "<link href='styles.css' rel='stylesheet' type='text/css'>";

                $this->page->requires->js_call_amd('mod_gmpregdiarias/js_competencia_cpu', 'init');

                $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-contianer-menu-opciones"));
                $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-contianer-menu-opcion gmpregdiarias-contianer-menu-opcion-js", "id"=>"gmpregdiarias-contianer-menu-opcion-scores"));
                $html.= html_writer::nonempty_tag('h3', 'Tabla puntuaciones',array());
                $html.= html_writer::end_tag('div');
                $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-contianer-menu-opcion-activa gmpregdiarias-contianer-menu-opcion-js", "id"=>"gmpregdiarias-contianer-menu-opcion-inicio"));
                $html.= html_writer::nonempty_tag('h3', 'Inicio',array());
                $html.= html_writer::end_tag('div');
                $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-contianer-menu-opcion gmpregdiarias-contianer-menu-opcion-js", "id"=>"gmpregdiarias-contianer-menu-opcion-historial"));
                $html.= html_writer::nonempty_tag('h3', 'Historial',array());
                $html.= html_writer::end_tag('div');
                $html.= html_writer::end_tag('div');
                $html.= html_writer::start_tag('div', array("id"=>"gmpregdiarias-activity-container"));



                $html.= html_writer::start_tag('div', array("id"=>"gmpregdiarias-contianer-menu-opcion-inicio-container", "class"=>"gmpregdiarias-section-contianer-menu-opcion"));
                $html.= html_writer::nonempty_tag('h1', "Computadoras vencidas", array("class" =>"gmpregdiarias-titulo"));
                $html.= html_writer::start_tag('table', array("id"=>"gmpregdiarias-table-cpu-defeated"));
                $html.= html_writer::start_tag('thead', array());
                $html.= html_writer::nonempty_tag('tr', $nombresDificultades, array());
                $html.= html_writer::end_tag('thead', array());
                $html.= html_writer::start_tag('tbody', array());
                $html.= html_writer::nonempty_tag('tr',$compusVencidas , array());
                $html.= html_writer::end_tag('tbody', array());
                $html.= html_writer::end_tag('table', array());



                $html.= html_writer::start_tag('form',
                    array('action' => new moodle_url('/mod/gmpregdiarias/attempt.php',
                        array('userid' => $moodleUserId, 'gmuserid' => $userid ,'instance' => $gmpregdiarias->id, 'id' => $id)), 'method' => 'post',
                        'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                        'id' => 'responseform'));
                $html.= html_writer::nonempty_tag('h3', "<b>Desafiar computadora</b>", array("class" =>"gmpregdiarias-container-card-title"));
                $html.= html_writer::start_tag('div', array("class" =>"gmpregdiarias-card-element"));
                $html.= html_writer::nonempty_tag('p', "Seleccione una dificultad: ",array());
                $html.= html_writer::nonempty_tag('select', $valoresSelect, array("class" => "gmpregdiarias-half-container", "name" => "dificultad"));
                $html.= html_writer::end_tag('div', array());
                $html.= html_writer::start_tag('div', array("class" =>"gmpregdiarias-card-element"));
                $html.= html_writer::empty_tag('input', array("type" =>"submit", "value"=>"Empezar", "class" => "gmpregdiarias-button btn btn-primary gmpregdiarias-container-card-button" ));
                $html.= html_writer::end_tag('div', array());
                $html.= html_writer::end_tag('form');

                $html.= html_writer::end_tag('div', array());*/

                $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";

                $display .= html_writer::start_tag('div');

                $display .= html_writer::start_tag('h2',array('class' => 'gmpregdiarias-titulo'));

                $display .= html_writer::start_tag('b');

                $display .= 'Contesta tu pregunta diaria!!';

                $display .= html_writer::end_tag('b');

                $display .= html_writer::end_tag('h2');

                $display .= html_writer::end_tag('div');

                $display .= html_writer::start_tag('div');

                $display .= html_writer::start_tag('form',
                    array('action' => new moodle_url('/mod/gmpregdiarias/attempt.php',
                        array('userid' => $moodleUserId, 'gmuserid' => $userid ,'instance' => $gmpregdiarias->id, 'id' => $id)), 'method' => 'post',
                        'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                        'id' => 'responseform'));

                $display.= html_writer::empty_tag('input', array("type" =>"submit", "value"=>"Contestar", "class" => "gmpregdiarias-button btn btn-primary gmpregdiarias-container-card-button" ));

                $display  .= html_writer::end_tag('form');

                $display .= html_writer::end_tag('div');

                return $display;

            }else{

                $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";

                $display .= html_writer::start_tag('div');

                $display .= html_writer::start_tag('h2',array('class' => 'gmpregdiarias-titulo'));

                $display .= html_writer::start_tag('b');

                $display .= 'La pregunta diaria ya fue contestada, regresa mañana!';

                $display .= html_writer::end_tag('b');

                $display .= html_writer::end_tag('h2');

                $display .= html_writer::end_tag('div');

                return $display;

            }


            /*return $html.$this->render_scores_page($gmpregdiarias, $userid,$dificultades,$moodleUserId);*/
        }



    public function render_scores_page($gmpregdiarias, $userid, $dificultades, $moodleUserId)
        {
            $leaderboards = [];
            $leaderboardsMax = [];
            $valoresSelect = '';
            for($i=0; $i<sizeof($dificultades);$i++)
                {
                    $leaderboards[] = array();
                    $leaderboardsMax[] = array();
                    $valoresSelect.= html_writer::nonempty_tag('option', $dificultades[$i]->nombre, array("value"=> $dificultades[$i]->id));
                }

            $primerosIntentos = $this->obtener_primeros_intentos($gmpregdiarias->id);

            foreach($primerosIntentos as $intento)
                {
                    $leaderboards[$intento->gmdl_dificultad_cpu_id-1][] = $intento;
                }

            $mejoresIntentos = $this->obtener_mejores_intentos($gmpregdiarias->id);

            foreach($mejoresIntentos as $intento)
                {
                    $leaderboardsMax[$intento->gmdl_dificultad_cpu_id-1][] = $intento;
                }

            $html= html_writer::start_tag('div', array("id"=>"gmpregdiarias-contianer-menu-opcion-scores-container", "class"=>"gmpregdiarias-section-contianer-menu-opcion"));
            $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-contianer-card"));
            $html.= html_writer::start_tag('div', array("class" =>"gmpregdiarias-card-element"));
            $html.= html_writer::nonempty_tag('p', "Seleccione una dificultad: ",array());
            $html.= html_writer::nonempty_tag('select', $valoresSelect, array("class" => "gmpregdiarias-half-container", "id" => "gmpregdiarias-select-scores-dificultad"));
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-contianer-leaderdoard"));
            $i = 0;
            foreach($dificultades as $dificultad)
                {
                    $leaderboard = $leaderboards[$i];
                    $html.= html_writer::start_tag('div', array("class" =>"gmpregdiarias-container-niveles", "id"=>"gmpregdiarias-container-nivel-".$dificultad->id));
                    $html.= html_writer::nonempty_tag('div', $dificultad->nombre, array("class" => "gmpregdiarias-linea-nivel-".$dificultad->id));
                    $html.= html_writer::start_tag('div', array("class" =>"gmpregdiarias-container-half-containers", "id"=>"gmpregdiarias-contenedor-nivel-".$dificultad->id));
                    $leaderboardMax = $leaderboardsMax[$i];
                    $cabezeraTabla= html_writer::start_tag('thead', array());
                    $cabezeraTabla.= html_writer::nonempty_tag('tr', "<th> Posici&oacute;n </th> <th> Nombre </th> <th> Puntuaci&oacute;n </th>", array("class"=>"gmpregdiarias-color-cpu-nivel-".$dificultad->id));
                    $cabezeraTabla.= html_writer::end_tag('thead', array());
                    $j=1;
                    $contenidoTablaPrimerIntento= html_writer::start_tag('tbody', array());
                    $ultimosPuntos = 0;
                    $primeraIteracion = 1;
                    foreach($leaderboard as $intento)
                        {
                            if( $ultimosPuntos> $intento->puntos && $primeraIteracion == 0)
                                {
                                    $ultimosPuntos = $puntos;
                                    $j--;
                                    $primeraIteracion = 1;
                                }
                            $contenidoTablaPrimerIntento.= html_writer::start_tag('tr');
                            if($j==1)
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('td');
                                    $contenidoTablaPrimerIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmpregdiarias-imagen-trophy"));
                                    $contenidoTablaPrimerIntento.= html_writer::end_tag('td');
                                }
                            else if($j==2)
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('td');
                                    $contenidoTablaPrimerIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmpregdiarias-imagen-trophy"));
                                    $contenidoTablaPrimerIntento.= html_writer::end_tag('td');
                                }
                            else if($j ==3)
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('td');
                                    $contenidoTablaPrimerIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmpregdiarias-imagen-trophy"));
                                    $contenidoTablaPrimerIntento.= html_writer::end_tag('td');
                                }
                            else
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', $j."°", array());
                                }

                                $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', $intento->firstname." ".$intento->lastname, array());
                                $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', $intento->puntos, array());
                                $contenidoTablaPrimerIntento.= html_writer::end_tag('tr');
                            $j++;
                        }
                    if($j == 1)
                        {
                            $contenidoTablaPrimerIntento.= html_writer::start_tag('tr', array("class"=>"gmpregdiarias-no-lugar"));
                            $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', "0°", array());
                            $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', "Sin participantes", array());
                            $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', " - - - ", array());
                            $contenidoTablaPrimerIntento.= html_writer::end_tag('tr');
                        }
                    $contenidoTablaPrimerIntento.= html_writer::end_tag('tbody', array());
                    $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-half-container"));
                    $html.= html_writer::nonempty_tag('h3', 'Primeros intentos',array());
                    $html.= html_writer::nonempty_tag('table', $cabezeraTabla.$contenidoTablaPrimerIntento, array("class"=>"gmpregdiarias-table-scores"));
                    $html.= html_writer::end_tag('div', array());
                    $j=1;
                    $primeraIteracion = 1;
                    $contenidoTablaMejorIntento= html_writer::start_tag('tbody', array());
                    $ultimosPuntos = 0;
                    foreach($leaderboardMax as $intento)
                        {
                            if( $ultimosPuntos> $intento->puntos && $primeraIteracion == 0)
                                {
                                    $ultimosPuntos = $puntos;
                                    $j--;
                                    $primeraIteracion = 0;
                                }
                            $contenidoTablaMejorIntento.= html_writer::start_tag('tr');
                            if($j==1)
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('td');
                                    $contenidoTablaMejorIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmpregdiarias-imagen-trophy"));
                                    $contenidoTablaMejorIntento.= html_writer::end_tag('td');
                                }
                            else if($j==2)
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('td');
                                    $contenidoTablaMejorIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmpregdiarias-imagen-trophy"));
                                    $contenidoTablaMejorIntento.= html_writer::end_tag('td');
                                }
                            else if($j ==3)
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('td');
                                    $contenidoTablaMejorIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmpregdiarias-imagen-trophy  "));
                                    $contenidoTablaMejorIntento.= html_writer::end_tag('td');
                                }
                            else
                                {
                                    $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', $j."°", array());
                                }

                                $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', $intento->firstname." ".$intento->lastname, array());
                                $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', $intento->puntos, array());
                                $contenidoTablaMejorIntento.= html_writer::end_tag('tr');
                            $j++;
                        }
                    if($j == 1)
                        {
                            $contenidoTablaMejorIntento.= html_writer::start_tag('tr', array("class"=>"gmpregdiarias-no-lugar"));
                            $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', "0°", array());
                            $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', "Sin participantes", array());
                            $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', " - - - ", array());
                            $contenidoTablaMejorIntento.= html_writer::end_tag('tr');
                        }
                    $contenidoTablaMejorIntento.= html_writer::end_tag('tbody', array());
                    $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-half-container"));
                    $html.= html_writer::nonempty_tag('h3', 'Mejores intentos',array());
                    $html.= html_writer::nonempty_tag('table', $cabezeraTabla.$contenidoTablaMejorIntento,array("class"=>"gmpregdiarias-table-scores"));
                    $html.= html_writer::end_tag('div', array());
                    $html.= html_writer::end_tag('div', array());
                    $html.= html_writer::end_tag('div', array());
                    $i++;
                }
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());
            return $html.$this->render_attempt_page($gmpregdiarias, $dificultades, $moodleUserId);
        }

    public function render_attempt_page($gmpregdiarias, $dificultades, $moodleUserId)
        {
            $intentos = $this->obtener_intentos_usuario($moodleUserId, $gmpregdiarias->id);

            $html = ' ';
            $html.= html_writer::start_tag('div', array("id"=>"gmpregdiarias-contianer-menu-opcion-historial-container", "class"=>"gmpregdiarias-section-contianer-menu-opcion"));
            $html.= html_writer::nonempty_tag('h1', 'Intentos realizados ',array("class" =>"gmpregdiarias-titulo"));
            $cabezeraTabla= html_writer::start_tag('thead', array());
            $cabezeraTabla.= html_writer::nonempty_tag('tr', '<th> Dificultad </th> <th> Puntos obtenidos </th> <th> Puntos computadora </th> <th> Victoria/Derrota</th>', array());
            $cabezeraTabla.= html_writer::end_tag('thead', array());
            $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-container gmpregdiarias-container-overflow-eighty-height"));
            $html.= html_writer::start_tag('div', array("class"=>"gmpregdiarias-container-scroll"));

            $contenidoTabla = html_writer::start_tag('tbody', array());
            foreach($intentos as $intento)
                {
                    $contenidoTabla.= html_writer::start_tag('tr', array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', $dificultades[$intento->gmdl_dificultad_cpu_id-1]->nombre,array("class" => "gmpregdiarias-color-cpu-nivel-".$dificultades[$intento->gmdl_dificultad_cpu_id-1]->id));
                    $contenidoTabla.= html_writer::nonempty_tag('td', $intento->puntuacion_usuario, array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', $intento->puntuacion_cpu, array());
                    if($intento->puntuacion_usuario >= $intento->puntuacion_cpu)
                        {

                            $contenidoTabla.= html_writer::start_tag('td', array());
                            $contenidoTabla.= html_writer::empty_tag('img', array("src"=>"pix/cpu_vencida.png", "class"=> 'gmpregdiarias-imagen-cpu'));
                            $contenidoTabla.= html_writer::end_tag('td', array());
                        }
                    else
                        {
                            $contenidoTabla.= html_writer::start_tag('td', array());
                            $contenidoTabla.= html_writer::empty_tag('img', array("src"=>"pix/cpu_activa.png", "class"=> 'gmpregdiarias-imagen-cpu'));
                            $contenidoTabla.= html_writer::end_tag('td', array());
                        }
                    $contenidoTabla.= html_writer::end_tag('tr', array());
                }
            if(sizeof($intentos) == 0)
                {
                    $contenidoTabla.= html_writer::start_tag('tr', array("class" => "gmpregdiarias-no-lugar"));
                    $contenidoTabla.= html_writer::nonempty_tag('td', "Sin intentos", array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', "- - - -", array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', "- - - -", array());
                    $contenidoTabla.= html_writer::end_tag('tr', array());
                }
            $contenidoTabla.= html_writer::end_tag('tbody', array());
            $html.= html_writer::nonempty_tag('table', $cabezeraTabla.$contenidoTabla, array("id"=>"gmpregdiarias-table-tries"));
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());

            $html.= html_writer::end_tag('div');
            return $html;
        }

    private function obtener_todas_dificultades()
        {
            global $DB;
            return array_values($DB->get_records($table='gmdl_dificultad_cpu', $conditions=null, $sort='id', $fields='*', $limitfrom=0, $limitnum=0));
        }


    private function obtener_intentos_usuario($moodleUserId, $gmpregdiariasid)
        {
            global $DB;
            $sql ="SELECT gmdl_dificultad_cpu_id, puntuacion_usuario, puntuacion_cpu";
            $sql.=" FROM {gmdl_intento}";
            $sql.=" JOIN {gmdl_usuario} ON {gmdl_usuario}.id = {gmdl_intento}.gmdl_usuario_id";
            $sql.=" JOIN {user} ON {user}.id = {gmdl_usuario}.mdl_id_usuario";
            $sql.=" WHERE {user}.id = ". $moodleUserId;
            $sql.=" AND {gmdl_intento}.gmdlcompcpu_id =".$gmpregdiariasid;
            $sql.=" AND fecha_fin IS NOT NULL";
            $sql.=" AND {gmdl_intento}.gmdlcompcpu_id = ".$gmpregdiariasid;
            $sql.=" ORDER BY fecha_fin DESC";

            return $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);

        }

    private function obtener_cpus_vencidas($instancia, $usuario)
        {
            global $DB;
            $sql = " SELECT gmdl_dificultad_cpu_id, COUNT(id)  FROM {gmdl_intento}";
            $sql.= " WHERE puntuacion_usuario >= puntuacion_cpu";
            $sql.= " AND gmdl_usuario_id = $usuario";
            $sql.= " AND gmdlcompcpu_id = ".$instancia;
            $sql.= " GROUP BY 1;";

            return $DB->get_records_sql($sql, null, $limitfrom = 0, $limitnum = 0);
        }
    private function obtener_primeros_intentos($instancia)
        {
            global $DB;
            $sql = "SELECT b.* FROM";
		    $sql.= " (";
			$sql.= " SELECT gmdl_usuario_id, gmdl_dificultad_cpu_id,  MIN(fecha_fin) as minima";
			$sql.= " FROM {gmdl_intento} ";
			$sql.= " WHERE fecha_fin IS NOT NULL";
			$sql.= " AND gmdlcompcpu_id = ".$instancia;
            $sql.= " GROUP BY 1, 2";
		    $sql.= " ) AS a";
            $sql.= " JOIN";
            $sql.= " (";
            $sql.= " SELECT gmdl_usuario_id, gmdl_dificultad_cpu_id, puntuacion_usuario AS puntos, fecha_fin, username, firstname, lastname";
            $sql.= " FROM {gmdl_intento}, {user}, {gmdl_usuario}";
            $sql.= " WHERE gmdlcompcpu_id = ".$instancia." AND";
            $sql.= " {user}.id = {gmdl_usuario}.mdl_id_usuario AND";
            $sql.= " {gmdl_usuario}.id = {gmdl_intento}.gmdl_usuario_id ";
            $sql.= " ) AS b";
            $sql.="  ON";
			$sql.= " a.gmdl_usuario_id = b.gmdl_usuario_id AND";
			$sql.= " a.gmdl_dificultad_cpu_id = b.gmdl_dificultad_cpu_id AND";
			$sql.= " a.minima = b.fecha_fin";
            $sql.= " ORDER BY b.puntos DESC";

            return $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);
        }
    private function obtener_mejores_intentos($instancia)
        {
            global $DB;
            $sql =" SELECT  a.*, username, firstname, lastname FROM";
            $sql.=" (SELECT gmdl_dificultad_cpu_id, gmdl_usuario_id, MAX(puntuacion_usuario) AS puntos";
            $sql.=" FROM {gmdl_intento}";
            $sql.=" WHERE gmdlcompcpu_id = ".$instancia;
            $sql.="  AND fecha_fin IS NOT NULL";
            $sql.=" GROUP BY 1,2 ) as a, {user}, {gmdl_usuario}";
            $sql.=" WHERE {user}.id =  {gmdl_usuario}.mdl_id_usuario AND";
            $sql.=" {gmdl_usuario}.id = a.gmdl_usuario_id";
            $sql.=" ORDER BY a.puntos DESC";
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
}
