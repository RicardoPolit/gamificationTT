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
 * The main renderer for mod_gmcompcpu
 *
 * @package    mod_gmcompcpu
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The main renderer for mod_gmcompcpu
 *
 * @package    mod_gmcompcpu
 * @copyright  2016 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_gmcompcpu_renderer extends plugin_renderer_base {
    /**
     * Initialises the game and returns its HTML code
     *
     * @param stdClass $gmcompcpu The gmcompcpu to be added
     * @param context $context The context
     * @return string The HTML code of the game
     */
    public function render_questions($gmcompcpu, $cm,$userid,$dificultad,$gmuserid,$id) {
        global $DB;

        $intento = (object)[
            'gmdl_dificultad_cpu_id' => $dificultad,
            'gmdlcompcpu_id' => $gmcompcpu->id,
            'gmdl_usuario_id' => $gmuserid,
            'fecha_inicio' => time()
        ];

        $intento->id =  $DB->insert_record('gmdl_intento',$intento);

        $categoryid = explode(',', $gmcompcpu->questioncategory)[0];

        $questionids = array_keys($DB->get_records('question', array('category' => intval($categoryid)), '', 'id'));

        $context = context_module::instance($cm->id);

        /*$quizobj = quiz::create($cm->instance, $userid);*/

        $quba = question_engine::make_questions_usage_by_activity('mod_gmcompcpu', $context);

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
            array('action' => new moodle_url('/mod/gmcompcpu/processattempt.php',
                array('id' => $quba->get_id(),'cm' => $cm->instance,'userid' => $userid)), 'method' => 'post',
                'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                'id' => 'responseform'));

        /*$this->page->requires->js_init_call('M.core_question_engine.init_form',
            array('#responseform'), false, 'core_question_engine');*/

        $display .= '<input type="hidden" name="slots" value="' . implode(',', $idstoslots) . "\" />\n";
        $display .= '<input type="hidden" name="scrollpos" value="" />';
        $display .= '<input type="hidden" name="intentoid" value='.$intento->id.' />';
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


        $display .= html_writer::start_tag('div', array("class"=>"gmcompcpu-container"));

        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Terminar', 'class' => "mod_quiz-next-nav btn btn-primary gmcompcpu-button", "style"=>"margin:auto; width: 25%;"));
        $display .= html_writer::end_tag('div');

        $display .= html_writer::end_tag('div');
        $display .= html_writer::end_tag('form');

        return $display;
    }


    public function render_results_attempt($userScore,$cpuScore){

        $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";

        if($userScore >= $cpuScore){

            $mensaje = 'Felicidades! Computadora derrotada';
            $class = 'ganador';

        }else{

            $mensaje = 'Oh no!, Debes practicar mas!';
            $class = 'perdedor';

        }

        $display .= html_writer::start_tag('div');

        $display .= html_writer::start_tag('h2',array('class' => 'gmcompcpu-titulo-'.$class));

        $display .= html_writer::start_tag('b');

        $display .= $mensaje;

        $display .= html_writer::end_tag('b');

        $display .= html_writer::end_tag('h2');

        $display .= html_writer::end_tag('div');

        $display .= html_writer::start_tag('div',array('class' => 'gmcompcpu-container'));

        $display .= html_writer::start_tag('div',array('class' => 'gmcompcpu-half-container'));


        $display .= html_writer::start_tag('h3');

        $display .= html_writer::start_tag('b');

        $display .= 'Tu puntuacion: '.$userScore;

        $display .= html_writer::end_tag('b');

        $display .= html_writer::end_tag('h3');


        $display .= html_writer::end_tag('div');

        $display .= html_writer::start_tag('div',array('class' => 'gmcompcpu-half-container'));

        $display .= html_writer::start_tag('h3');

        $display .= html_writer::start_tag('b');

        $display .= 'Puntuacion cpu: '.$cpuScore;

        $display .= html_writer::end_tag('b');

        $display .= html_writer::end_tag('h3');

        $display .= html_writer::end_tag('div');

        $display .= html_writer::end_tag('div');

        return $display;

    }




    public function render_main_page($gmcompcpu, $userid,$id)
        {
            $moodleUserId = $userid;
            global $DB;
            $userid = $DB->get_record('gmdl_usuario', $conditions=array("mdl_id_usuario" => $userid), $fields='*', $strictness=IGNORE_MISSING)->id;
            $dificultades = array_values($DB->get_records($table='gmdl_dificultad_cpu', $conditions=null, $sort='id', $fields='*', $limitfrom=0, $limitnum=0));
            $sql = " SELECT gmdl_dificultad_cpu_id, COUNT(id)  FROM {gmdl_intento}";
            $sql.= " WHERE puntuacion_usuario >= puntuacion_cpu";
            $sql.= " AND gmdl_usuario_id = $userid";
            $sql.= " AND gmdlcompcpu_id = ".$gmcompcpu->id;
            $sql.= " GROUP BY 1;";
            try {
                $victorias = $DB->get_records_sql($sql, null, $limitfrom = 0, $limitnum = 0);
                $querysucess = TRUE;
            } catch (dml_exception $e) {
                $querysucess = false;
            }
            $compusVencidas = '';
            $nombresDificultades = '';
            $valoresSelect = '';
            foreach($dificultades as $dificultad)
                {
                    $noEncontrado = TRUE;
                    $nombresDificultades.= html_writer::nonempty_tag('th', $dificultad->nombre);
                    foreach($victorias as $victoria)
                        {
                            if($victoria->gmdl_dificultad_cpu_id == $dificultad->id && $noEncontrado)
                            {
                                $compusVencidas.= html_writer::nonempty_tag('td', 'S&iacute;', array("class"=> 'gmcompcpu-cpu-vencida'));
                                $noEncontrado = FALSE;
                            }
                        }
                    if($noEncontrado)
                        {
                            $compusVencidas.= html_writer::nonempty_tag('td', 'No', array("class"=> 'gmcompcpu-cpu'));
                        }


                    $valoresSelect.= html_writer::nonempty_tag('option', $dificultad->nombre, array("value"=> $dificultad->id));
                }


            $html = "<link href='styles.css' rel='stylesheet' type='text/css'>";


            //Graficando el menú superior



            //Graficando la tabla que muestra qué computadoras ha vencido el usuario

            $this->page->requires->js_call_amd('mod_gmcompcpu/js_competencia_cpu', 'init');

            $html.= html_writer::tag('div', '',array("class" =>"gmcompcpu-linea"));


            $html.= html_writer::start_tag('div', array("id"=>"gmcompcpu-container-inicio"));
            $html.= html_writer::nonempty_tag('h1', "Computadoras vencidas", array("class" =>"gmcompcpu-titulo"));
            $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-container"));
            $html.= html_writer::start_tag('table', array());
            $html.= html_writer::start_tag('thead', array());
            $html.= html_writer::nonempty_tag('tr', $nombresDificultades, array());
            $html.= html_writer::end_tag('thead', array());
            $html.= html_writer::start_tag('tbody', array());
            $html.= html_writer::nonempty_tag('tr',$compusVencidas , array());
            $html.= html_writer::end_tag('tbody', array());
            $html.= html_writer::end_tag('table', array());
            $html.= html_writer::end_tag('div', array());



            //Iniciando el from, para poder mandar la información del choice a la página de preguntas

            $html.= html_writer::start_tag('form',
                array('action' => new moodle_url('/mod/gmcompcpu/attempt.php',
                    array('userid' => $moodleUserId, 'gmuserid' => $userid ,'instance' => $gmcompcpu->id, 'id' => $id)), 'method' => 'post',
                    'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                    'id' => 'responseform'));
            $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-container"));
            $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-container-card"));
            $html.= html_writer::nonempty_tag('h3', "<b>Desafiar computadora</b>", array("class" =>"gmcompcpu-container-card-title"));
            $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-card-element"));
            $html.= html_writer::nonempty_tag('select', $valoresSelect, array("class" => "gmcompcpu-half-container", "name" => "dificultad"));
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-card-element"));
            $html.= html_writer::empty_tag('input', array("type" =>"submit", "value"=>"Empezar", "class" => "gmcompcpu-button btn btn-primary gmcompcpu-container-card-button" ));
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('form');





            $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-container"));
            $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-half-container"));
            $html.= html_writer::nonempty_tag('button', " << Ver tabla de puntuaciones",array("class" =>"btn btn-primary gmcompcpu-half-container gmcompcpu-button", "id"=>"gmcompcpu-ver-scores"));
            $html.= html_writer::end_tag('div', '',array());
            $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-half-container"));
            $html.= html_writer::nonempty_tag('button', "Ver intentos >>",array("class" =>"btn btn-primary gmcompcpu-half-container gmcompcpu-button", "id"=>"gmcompcpu-ver-intentos"));
            $html.= html_writer::end_tag('div', '',array());
            $html.= html_writer::end_tag('div', '',array());
            $html.= html_writer::end_tag('div', array());


            return $html.$this->render_scores_page($gmcompcpu, $userid,$dificultades,$moodleUserId);
        }



        public function render_scores_page($gmcompcpu, $userid, $dificultades, $moodleUserId)
        {
            global $DB;
            /*$dificultades = array_values($DB->get_records($table='gmdl_dificultad_cpu', $conditions=null, $sort='id', $fields='*', $limitfrom=0, $limitnum=0));*/
            $leaderboards = [];
            $leaderboardsMax = [];
            for($i=0; $i<sizeof($dificultades);$i++)
                {
                    $leaderboards[] = array();
                    $leaderboardsMax[] = array();
                }
            $sql = "SELECT b.* FROM";
		    $sql.= " (";
			$sql.= " SELECT gmdl_usuario_id, gmdl_dificultad_cpu_id,  MIN(fecha_fin) as minima";
			$sql.= " FROM {gmdl_intento} ";
			$sql.= " WHERE fecha_fin IS NOT NULL";
			$sql.= " AND gmdlcompcpu_id = ".$gmcompcpu->id;;
            $sql.= " GROUP BY 1, 2";
		    $sql.= " ) AS a";
            $sql.= " JOIN";
            $sql.= " (";
            $sql.= " SELECT gmdl_usuario_id, gmdl_dificultad_cpu_id, puntuacion_usuario AS puntos, fecha_fin, username, firstname, lastname";
            $sql.= " FROM {gmdl_intento}, {user}, {gmdl_usuario}";
            $sql.= " WHERE gmdlcompcpu_id = ".$gmcompcpu->id." AND";
            $sql.= " {user}.id = {gmdl_usuario}.mdl_id_usuario AND";
            $sql.= " {gmdl_usuario}.id = {gmdl_intento}.gmdl_usuario_id ";
            $sql.= " ) AS b";
            $sql.="  ON";
			$sql.= " a.gmdl_usuario_id = b.gmdl_usuario_id AND";
			$sql.= " a.gmdl_dificultad_cpu_id = b.gmdl_dificultad_cpu_id AND";
			$sql.= " a.minima = b.fecha_fin";
            $sql.= " ORDER BY b.puntos DESC";

            $primerosIntentos = $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);

            foreach($primerosIntentos as $intento)
                {
                    $leaderboards[$intento->gmdl_dificultad_cpu_id-1][] = $intento;
                }

            $sql =" SELECT  a.*, username, firstname, lastname FROM";
            $sql.=" (SELECT gmdl_dificultad_cpu_id, gmdl_usuario_id, MAX(puntuacion_usuario) AS puntos";
            $sql.=" FROM {gmdl_intento}";
            $sql.=" WHERE gmdlcompcpu_id = ".$gmcompcpu->id;
            $sql.="  AND fecha_fin IS NOT NULL";
            $sql.=" GROUP BY 1,2 ) as a, {user}, {gmdl_usuario}";
            $sql.=" WHERE {user}.id =  {gmdl_usuario}.mdl_id_usuario AND";
            $sql.=" {gmdl_usuario}.id = a.gmdl_usuario_id";
            $sql.=" ORDER BY a.puntos DESC";
            $mejoresIntentos = $primerosIntentos = $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);

            foreach($mejoresIntentos as $intento)
                {
                    $leaderboardsMax[$intento->gmdl_dificultad_cpu_id-1][] = $intento;
                }



            #$html = "<link href='styles.css' rel='stylesheet' type='text/css'>";
            $html= html_writer::start_tag('div', array("id"=>"gmcompcpu-container-posiciones"));
            $i = 0;
            foreach($dificultades as $dificultad)
                {
                    $leaderboard = $leaderboards[$i];
                    $html.= html_writer::nonempty_tag('div', $dificultad->nombre, array("class" => "gmcompcpu-linea-nivel-".$dificultad->id));
                    $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-container"));
                    $leaderboardMax = $leaderboardsMax[$i];
                    $cabezeraTabla= html_writer::start_tag('thead', array());
                    $cabezeraTabla.= html_writer::nonempty_tag('tr', "<th> Posici&oacute;n </th> <th> Nombre </th> <th> Puntuaci&oacute;n </th>", array());
                    $cabezeraTabla.= html_writer::end_tag('thead', array());
                    $j=1;
                    $contenidoTablaPrimerIntento= html_writer::start_tag('tbody', array());
                    foreach($leaderboard as $intento)
                        {
                            if($j==1)
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-primer-lugar"));
                                }
                            else if($j==2)
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-segundo-lugar"));
                                }
                            else if($j ==3)
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-tercer-lugar"));
                                }
                            else
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-n-lugar"));
                                }
                                $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', $j."°", array());
                                $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', $intento->firstname." ".$intento->lastname, array());
                                $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', $intento->puntos, array());
                                $contenidoTablaPrimerIntento.= html_writer::end_tag('tr');
                            $j++;
                        }
                    if($j == 1)
                        {
                            $contenidoTablaPrimerIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-no-lugar"));
                            $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', "0°", array());
                            $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', "Sin participantes", array());
                            $contenidoTablaPrimerIntento.= html_writer::nonempty_tag('td', " - - - ", array());
                            $contenidoTablaPrimerIntento.= html_writer::end_tag('tr');
                        }
                    $contenidoTablaPrimerIntento.= html_writer::end_tag('tbody', array());
                    $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-half-container"));
                    $html.= html_writer::nonempty_tag('table', $cabezeraTabla.$contenidoTablaPrimerIntento,array());
                    $html.= html_writer::end_tag('div', array());
                    $j=1;
                    $contenidoTablaMejorIntento= html_writer::start_tag('tbody', array());
                    foreach($leaderboardMax as $intento)
                        {
                            if($j==1)
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-primer-lugar"));
                                }
                            else if($j==2)
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-segundo-lugar"));
                                }
                            else if($j ==3)
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-tercer-lugar"));
                                }
                            else
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-n-lugar"));
                                }
                                $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', $j."°", array());
                                $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', $intento->firstname." ".$intento->lastname, array());
                                $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', $intento->puntos, array());
                                $contenidoTablaMejorIntento.= html_writer::end_tag('tr');
                            $j++;
                        }
                    if($j == 1)
                        {
                            $contenidoTablaMejorIntento.= html_writer::start_tag('tr', array("class"=>"gmcompcpu-no-lugar"));
                            $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', "0°", array());
                            $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', "Sin participantes", array());
                            $contenidoTablaMejorIntento.= html_writer::nonempty_tag('td', " - - - ", array());
                            $contenidoTablaMejorIntento.= html_writer::end_tag('tr');
                        }
                    $contenidoTablaMejorIntento.= html_writer::end_tag('tbody', array());
                    $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-half-container"));
                    $html.= html_writer::nonempty_tag('table', $cabezeraTabla.$contenidoTablaMejorIntento,array());
                    $html.= html_writer::end_tag('div', array());
                    $html.= html_writer::end_tag('div', array());
                    $i++;
                }
            $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-container"));
            $html.= html_writer::nonempty_tag('button', "Volver",array("class" =>"gmcompcpu-quarter-container btn btn-primary gmcompcpu-end-button-volver gmcompcpu-button", "id"=>"gmcompcpu-volver-posiciones"));
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());
            return $html.$this->render_attempt_page($gmcompcpu, $userid,$dificultades, $moodleUserId);
        }
    public function render_attempt_page($gmcompcpu, $userid,$dificultades, $moodleUserId)
        {
            $html = ' ';
            #$html = "<link href='styles.css' rel='stylesheet' type='text/css'>";
            global $DB;
            /*$dificultades = array_values($DB->get_records($table='gmdl_dificultad_cpu', $conditions=null, $sort='id', $fields='*', $limitfrom=0, $limitnum=0));*/

            $sql ="SELECT gmdl_dificultad_cpu_id, puntuacion_usuario, puntuacion_cpu";
            $sql.=" FROM {gmdl_intento}";
            $sql.=" JOIN {gmdl_usuario} ON {gmdl_usuario}.id = {gmdl_intento}.gmdl_usuario_id";
            $sql.=" JOIN {user} ON {user}.id = {gmdl_usuario}.mdl_id_usuario";
            $sql.=" WHERE {user}.id = ". $moodleUserId;
            $sql.=" AND {gmdl_intento}.gmdlcompcpu_id =".$gmcompcpu->id;
            $sql.=" AND fecha_fin IS NOT NULL";
            $sql.=" AND {gmdl_intento}.gmdl_comp_cpu_id = ".$gmcompcpu->id;
            $sql.=" ORDER BY fecha_fin DESC";

            $intentos = $DB->get_recordset_sql($sql, null, $limitfrom = 0, $limitnum = 0);
            $html.= html_writer::start_tag('div', array("id"=>"gmcompcpu-container-intentos"));
            $html.= html_writer::nonempty_tag('h1', 'Intentos realizados ',array("class" =>"gmcompcpu-titulo"));
            $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-container"));
            $cabezeraTabla= html_writer::start_tag('thead', array());
            $cabezeraTabla.= html_writer::nonempty_tag('tr', '<th> Dificultad </th> <th> Puntos obtenidos </th> <th> Puntos computadora </th> <th> Victoria/Derrota</th>',array());
            $cabezeraTabla.= html_writer::end_tag('thead', array());

            $contenidoTabla = html_writer::start_tag('tbody', array());
            foreach($intentos as $intento)
                {
                    $contenidoTabla.= html_writer::start_tag('tr', array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', $dificultades[$intento->gmdl_dificultad_cpu_id-1]->nombre,array("class" => "gmcompcpu-tabla-celda-nivel-".$dificultades[$intento->gmdl_dificultad_cpu_id-1]->id));
                    $contenidoTabla.= html_writer::nonempty_tag('td', $intento->puntuacion_usuario, array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', $intento->puntuacion_cpu, array());
                    if($intento->puntuacion_usuario >= $intento->puntuacion_cpu)
                        {
                            $contenidoTabla.= html_writer::nonempty_tag('td', ' ', array("class" => "gmcompcpu-cpu-vencida"));

                        }
                    else
                        {
                            $contenidoTabla.= html_writer::nonempty_tag('td', ' ', array("class"=>"gmcompcpu-tabla-celda-perdedora"));
                        }
                    $contenidoTabla.= html_writer::end_tag('tr', array());
                }
            if(sizeof($intentos) == 0)
                {
                    $contenidoTabla.= html_writer::start_tag('tr', array("class" => "gmcompcpu-no-lugar"));
                    $contenidoTabla.= html_writer::nonempty_tag('td', "Sin intentos", array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', "- - - -", array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', "- - - -", array());
                    $contenidoTabla.= html_writer::end_tag('tr', array());
                }
            $contenidoTabla.= html_writer::end_tag('tbody', array());
            $html.= html_writer::nonempty_tag('table', $cabezeraTabla.$contenidoTabla, array());
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-container"));
            $html.= html_writer::nonempty_tag('button', "Volver",array("class" =>" gmcompcpu-quarter-container btn btn-primary gmcompcpu-end-button-volver gmcompcpu-button", "id"=>"gmcompcpu-volver-intentos"));
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::tag('div', '',array("class" =>"gmcompcpu-linea"));
            return $html;
        }

}
