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

        $categoryid = explode(',', $gmcompcpu->mdl_question_categories_id)[0];

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
                $idstoslots[] = $quba->add_question($question, null);
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


    public function render_results_attempt($userScore, $cpuScore, $cmid){

        $display = "<link href='styles.css' rel='stylesheet' type='text/css'>";

        if($userScore >= $cpuScore){

            $mensaje = '¡Felicidades! Computadora derrotada';
            $class = 'ganador';

        }else{

            $mensaje = '¡Oh no!, Debes practicar m&aacute;s!';
            $class = 'perdedor';

        }

        $display .= html_writer::start_tag('div', array("id"=>"gmcompcpu-activity-container", "class"=>"gmcompcpu-container-results"));
            $display .= html_writer::start_tag('div');
                $display .= html_writer::start_tag('h2',array('class' => 'gmcompcpu-titulo-'.$class));
                    $display .= html_writer::start_tag('b');
                    $display .= $mensaje;
                    $display .= html_writer::end_tag('b');
                $display .= html_writer::end_tag('h2');
            $display .= html_writer::end_tag('div');
            $display .= html_writer::start_tag('div',array('class' => 'gmcompcpu-container-half-containers'));
                $display .= html_writer::start_tag('div',array('class' => 'gmcompcpu-half-container'));
                    $display .= html_writer::start_tag('h3');
                        $display .= html_writer::start_tag('b');
                        $display .= 'Tu puntuaci&oacute;n:   '.$userScore;
                        $display .= html_writer::end_tag('b');
                    $display .= html_writer::end_tag('h3');
                $display .= html_writer::end_tag('div');
                $display .= html_writer::start_tag('div',array('class' => 'gmcompcpu-half-container'));
                    $display .= html_writer::start_tag('h3');
                        $display .= html_writer::start_tag('b');
                            $display .= 'Puntuaci&oacute;n cpu:   '.$cpuScore;
                        $display .= html_writer::end_tag('b');
                    $display .= html_writer::end_tag('h3');
                $display .= html_writer::end_tag('div');
            $display .= html_writer::end_tag('div');
            $display .= html_writer::start_tag('form',
                array('action' => new moodle_url('/mod/gmcompcpu/view.php',
                    array('id' => $cmid)), 'method' => 'post',
                    'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                    'id' => 'responseform'));
            $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
                'value' => 'Volver', 'class' => "btn btn-primary gmcompcpu-button"));
            $display .= html_writer::end_tag('form');

        $display .= html_writer::end_tag('div');

        


        return $display;

    }




    public function render_main_page($gmcompcpu, $userid,$id)
        {
            $moodleUserId = $userid;
            $userid = $this->obtener_usuario_gamedle($moodleUserId);
            if(is_null($userid))
                {
                    $userid = $this->insertar_usuario($moodleUserId);
                }
            $dificultades = $this->obtener_todas_dificultades();
            $victorias = $this->obtener_cpus_vencidas($gmcompcpu->id, $userid);
            $compusVencidas = '';
            $nombresDificultades = '';
            $valoresSelect = '';
            foreach($dificultades as $dificultad)
                {
                    $noEncontrado = TRUE;
                    $nombresDificultades.= html_writer::nonempty_tag('th', $dificultad->nombre, array("class"=>""));
                    foreach($victorias as $victoria)
                        {
                            if($victoria->gmdl_dificultad_cpu_id == $dificultad->id && $noEncontrado)
                            {
                                $compusVencidas.= html_writer::start_tag('td', array());
                                $compusVencidas.= html_writer::empty_tag('img', array("src"=>"pix/cpu_v_".$dificultad->id.".png", "class"=> 'gmcompcpu-imagen-cpu'));
                                $compusVencidas.= html_writer::end_tag('td', array());
                                $noEncontrado = FALSE;
                            }
                        }
                    if($noEncontrado)
                        {
                            $compusVencidas.= html_writer::start_tag('td', array());
                            $compusVencidas.= html_writer::empty_tag('img', array("src"=>"pix/cpu_pv_".$dificultad->id.".png", "class"=> 'gmcompcpu-imagen-cpu'));
                            $compusVencidas.= html_writer::end_tag('td', array());
                        }


                    $valoresSelect.= html_writer::nonempty_tag('option', $dificultad->nombre, array("value"=> $dificultad->id));
                }


            $html = "<link href='styles.css' rel='stylesheet' type='text/css'>";

            $this->page->requires->js_call_amd('mod_gmcompcpu/js_competencia_cpu', 'init');

            $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-contianer-menu-opciones"));
                $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-contianer-menu-opcion-activa gmcompcpu-contianer-menu-opcion-js", "id"=>"gmcompcpu-contianer-menu-opcion-inicio"));
                    $html.= html_writer::nonempty_tag('h3', 'Inicio',array());
                $html.= html_writer::end_tag('div');
                $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-contianer-menu-opcion gmcompcpu-contianer-menu-opcion-js", "id"=>"gmcompcpu-contianer-menu-opcion-scores"));
                    $html.= html_writer::nonempty_tag('h3', 'Puntuaciones',array());
                $html.= html_writer::end_tag('div');
                $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-contianer-menu-opcion gmcompcpu-contianer-menu-opcion-js", "id"=>"gmcompcpu-contianer-menu-opcion-historial"));
                    $html.= html_writer::nonempty_tag('h3', 'Historial',array());
                $html.= html_writer::end_tag('div');
            $html.= html_writer::end_tag('div');
            $html.= html_writer::start_tag('div', array("id"=>"gmcompcpu-activity-container"));
            


            $html.= html_writer::start_tag('div', array("id"=>"gmcompcpu-contianer-menu-opcion-inicio-container", "class"=>"gmcompcpu-section-contianer-menu-opcion"));
                $html.= html_writer::nonempty_tag('h1', "Computadoras derrotadas", array("class" =>"gmcompcpu-titulo"));
                    $html.= html_writer::start_tag('table', array("id"=>"gmcompcpu-table-cpu-defeated"));
                    $html.= html_writer::start_tag('thead', array());
                    $html.= html_writer::nonempty_tag('tr', $nombresDificultades, array());
                    $html.= html_writer::end_tag('thead', array());
                    $html.= html_writer::start_tag('tbody', array());
                    $html.= html_writer::nonempty_tag('tr',$compusVencidas , array());
                    $html.= html_writer::end_tag('tbody', array());
                    $html.= html_writer::end_tag('table', array());
                    $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-contianer-left-align"));
                        $imagen1 = html_writer::empty_tag('img', array("src"=>"pix/cpu_ejemplo_pv.png", "class"=> 'gmcompcpu-imagen-cpu-resumen'));
                        $imagen2 = html_writer::empty_tag('img', array("src"=>"pix/cpu_ejemplo_v.png", "class"=> 'gmcompcpu-imagen-cpu-resumen'));
                        $html.= html_writer::nonempty_tag('p', "E &iacute;cono $imagen1 se transformará en $imagen2 cuando se haya vencido a la computadora en esa respectiva dificultad.", array());
                    $html.= html_writer::end_tag('div', array());


                $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-card-container"));
                    $html.= html_writer::start_tag('form',
                        array('action' => new moodle_url('/mod/gmcompcpu/attempt.php',
                            array('userid' => $moodleUserId, 'gmuserid' => $userid ,'instance' => $gmcompcpu->id, 'id' => $id)), 'method' => 'post',
                            'enctype' => 'multipart/form-data', 'accept-charset' => 'utf-8',
                            'id' => 'responseform'));
                    $html.= html_writer::nonempty_tag('h3', "<b>Desafiar computadora</b>", array("class" =>"gmcompcpu-container-card-title"));
                    $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-card-element"));
                    $html.= html_writer::nonempty_tag('p', "Seleccione una dificultad: ",array());
                    $html.= html_writer::nonempty_tag('select', $valoresSelect, array("class" => "gmcompcpu-half-container", "name" => "dificultad"));
                    $html.= html_writer::end_tag('div', array());
                    $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-card-element"));
                    $html.= html_writer::empty_tag('input', array("type" =>"submit", "value"=>"Empezar", "class" => "gmcompcpu-button btn btn-primary gmcompcpu-container-card-button" ));
                    $html.= html_writer::end_tag('div', array());
                    $html.= html_writer::end_tag('form');
                $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());


            return $html.$this->render_scores_page($gmcompcpu, $userid,$dificultades,$moodleUserId);
        }



    public function render_scores_page($gmcompcpu, $userid, $dificultades, $moodleUserId)
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
            
            $primerosIntentos = $this->obtener_primeros_intentos($gmcompcpu->id);

            foreach($primerosIntentos as $intento)
                {
                    $leaderboards[$intento->gmdl_dificultad_cpu_id-1][] = $intento;
                }

            $mejoresIntentos = $this->obtener_mejores_intentos($gmcompcpu->id);

            foreach($mejoresIntentos as $intento)
                {
                    $leaderboardsMax[$intento->gmdl_dificultad_cpu_id-1][] = $intento;
                }

            $html= html_writer::start_tag('div', array("id"=>"gmcompcpu-contianer-menu-opcion-scores-container", "class"=>"gmcompcpu-section-contianer-menu-opcion"));
            $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-contianer-card"));
            $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-card-element"));
            $html.= html_writer::nonempty_tag('p', "Seleccione una dificultad: ",array());
            $html.= html_writer::nonempty_tag('select', $valoresSelect, array("class" => "gmcompcpu-half-container", "id" => "gmcompcpu-select-scores-dificultad"));
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-contianer-leaderdoard"));
            $i = 0;
            foreach($dificultades as $dificultad)
                {
                    $leaderboard = $leaderboards[$i];
                    $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-container-niveles", "id"=>"gmcompcpu-container-nivel-".$dificultad->id));
                        $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-container-muestra-nivel"));
                            $html.= html_writer::empty_tag('img', array("src"=>"pix/cpu_pv_".$dificultad->id.".png", "class"=>"gmcompcpu-imagen-trophy"));
                                $html.= html_writer::nonempty_tag('div', $dificultad->nombre, array("class" => "gmcompcpu-linea-nivel-".$dificultad->id));
                            $html.= html_writer::empty_tag('img', array("src"=>"pix/cpu_v_".$dificultad->id.".png", "class"=>"gmcompcpu-imagen-trophy"));
                        $html.= html_writer::end_tag('div', array());
                    $html.= html_writer::start_tag('div', array("class" =>"gmcompcpu-container-half-containers", "id"=>"gmcompcpu-contenedor-nivel-".$dificultad->id));
                    $leaderboardMax = $leaderboardsMax[$i];
                    $cabezeraTabla= html_writer::start_tag('thead', array());
                    $cabezeraTabla.= html_writer::nonempty_tag('tr', "<th> Posici&oacute;n </th> <th> Nombre </th> <th> Puntuaci&oacute;n </th>", array(""));
                    $cabezeraTabla.= html_writer::end_tag('thead', array());
                    $j=1;
                    $contenidoTablaPrimerIntento= html_writer::start_tag('tbody', array());
                    $ultimosPuntos = 0;
                    $primeraIteracion = 1;
                    foreach($leaderboard as $intento)
                        {
                            if( $ultimosPuntos == $intento->puntos && $primeraIteracion == 0)
                                {
                                    $j--;
                                }
                            $contenidoTablaPrimerIntento.= html_writer::start_tag('tr');
                            if($j==1)
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('td');
                                    $contenidoTablaPrimerIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmcompcpu-imagen-trophy"));
                                    $contenidoTablaPrimerIntento.= html_writer::end_tag('td');
                                }
                            else if($j==2)
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('td');
                                    $contenidoTablaPrimerIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_second.png", "class"=>"gmcompcpu-imagen-trophy"));
                                    $contenidoTablaPrimerIntento.= html_writer::end_tag('td');
                                }
                            else if($j ==3)
                                {
                                    $contenidoTablaPrimerIntento.= html_writer::start_tag('td');
                                    $contenidoTablaPrimerIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_third.png", "class"=>"gmcompcpu-imagen-trophy"));
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
                            $primeraIteracion = 0;
                            $ultimosPuntos = $intento->puntos;
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
                    $html.= html_writer::nonempty_tag('h3', 'Primer intento',array());
                    $html.= html_writer::nonempty_tag('table', $cabezeraTabla.$contenidoTablaPrimerIntento, array("class"=>"gmcompcpu-table-scores"));
                    $html.= html_writer::end_tag('div', array());
                    $j=1;
                    $primeraIteracion = 1;
                    $contenidoTablaMejorIntento= html_writer::start_tag('tbody', array());
                    $ultimosPuntos = 0;
                    foreach($leaderboardMax as $intento)
                        {
                            if( $ultimosPuntos == $intento->puntos && $primeraIteracion == 0)
                                {
                                    $j--;
                                }
                            $contenidoTablaMejorIntento.= html_writer::start_tag('tr');
                            if($j==1)
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('td');
                                    $contenidoTablaMejorIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_first.png", "class"=>"gmcompcpu-imagen-trophy"));
                                    $contenidoTablaMejorIntento.= html_writer::end_tag('td');
                                }
                            else if($j==2)
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('td');
                                    $contenidoTablaMejorIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_second.png", "class"=>"gmcompcpu-imagen-trophy"));
                                    $contenidoTablaMejorIntento.= html_writer::end_tag('td');
                                }
                            else if($j ==3)
                                {
                                    $contenidoTablaMejorIntento.= html_writer::start_tag('td');
                                    $contenidoTablaMejorIntento.= html_writer::empty_tag('img', array("src"=>"pix/trophy_third.png", "class"=>"gmcompcpu-imagen-trophy  "));
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
                            $primeraIteracion = 0;
                            $ultimosPuntos = $intento->puntos;
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
                    $html.= html_writer::nonempty_tag('h3', 'Mejor intento',array());
                    $html.= html_writer::nonempty_tag('table', $cabezeraTabla.$contenidoTablaMejorIntento,array("class"=>"gmcompcpu-table-scores"));
                    $html.= html_writer::end_tag('div', array());
                    $html.= html_writer::end_tag('div', array());
                    $html.= html_writer::end_tag('div', array());
                    $i++;
                }
            $html.= html_writer::end_tag('div', array());
            $html.= html_writer::end_tag('div', array());
            return $html.$this->render_attempt_page($gmcompcpu, $dificultades, $moodleUserId);
        }
    public function render_attempt_page($gmcompcpu, $dificultades, $moodleUserId)
        {
            $intentos = $this->obtener_intentos_usuario($moodleUserId, $gmcompcpu->id); 

            $html = ' ';
            $html.= html_writer::start_tag('div', array("id"=>"gmcompcpu-contianer-menu-opcion-historial-container", "class"=>"gmcompcpu-section-contianer-menu-opcion"));
            $html.= html_writer::nonempty_tag('h1', 'Intentos realizados ',array("class" =>"gmcompcpu-titulo"));
            $cabezeraTabla= html_writer::start_tag('thead', array());
            $cabezeraTabla.= html_writer::nonempty_tag('tr', '<th> Dificultad </th> <th> Puntos obtenidos </th> <th> Puntos computadora </th> <th> Victoria/Derrota</th>', array());
            $cabezeraTabla.= html_writer::end_tag('thead', array());
            $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-container gmcompcpu-container-overflow-eighty-height"));
            $html.= html_writer::start_tag('div', array("class"=>"gmcompcpu-container-scroll"));

            $contenidoTabla = html_writer::start_tag('tbody', array());
            foreach($intentos as $intento)
                {
                    $contenidoTabla.= html_writer::start_tag('tr', array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', $dificultades[$intento->gmdl_dificultad_cpu_id-1]->nombre,array("class" => ""));
                    $contenidoTabla.= html_writer::nonempty_tag('td', $intento->puntuacion_usuario, array());
                    $contenidoTabla.= html_writer::nonempty_tag('td', $intento->puntuacion_cpu, array());
                    if($intento->puntuacion_usuario >= $intento->puntuacion_cpu)
                        {
                            
                            $contenidoTabla.= html_writer::start_tag('td', array());
                            $contenidoTabla.= html_writer::empty_tag('img', array("src"=>"pix/cpu_v_".$intento->gmdl_dificultad_cpu_id.".png", "class"=> 'gmcompcpu-imagen-cpu'));
                            $contenidoTabla.= html_writer::end_tag('td', array());
                        }
                    else
                        {
                            $contenidoTabla.= html_writer::start_tag('td', array());
                            $contenidoTabla.= html_writer::empty_tag('img', array("src"=>"pix/cpu_pv_".$intento->gmdl_dificultad_cpu_id.".png", "class"=> 'gmcompcpu-imagen-cpu'));
                            $contenidoTabla.= html_writer::end_tag('td', array());
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
            $html.= html_writer::nonempty_tag('table', $cabezeraTabla.$contenidoTabla, array("id"=>"gmcompcpu-table-tries"));
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


    private function obtener_intentos_usuario($moodleUserId, $gmcompcpuid)
        {
            global $DB;
            $sql ="SELECT gmdl_dificultad_cpu_id, puntuacion_usuario, puntuacion_cpu";
            $sql.=" FROM {gmdl_intento}";
            $sql.=" JOIN {gmdl_usuario} ON {gmdl_usuario}.id = {gmdl_intento}.gmdl_usuario_id";
            $sql.=" JOIN {user} ON {user}.id = {gmdl_usuario}.mdl_id_usuario";
            $sql.=" WHERE {user}.id = ". $moodleUserId;
            $sql.=" AND {gmdl_intento}.gmdlcompcpu_id =".$gmcompcpuid;
            $sql.=" AND fecha_fin IS NOT NULL";
            $sql.=" AND {gmdl_intento}.gmdlcompcpu_id = ".$gmcompcpuid;
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
