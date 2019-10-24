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
    public function render_questions($gmcompcpu, $cm,$userid) {
        global $DB;

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

        $options = new question_display_options();
        $options->marks = question_display_options::MAX_ONLY;
        $options->markdp = 2; // Display marks to 2 decimal places.
        $options->feedback = question_display_options::VISIBLE;
        $options->generalfeedback = question_display_options::HIDDEN;

        $display .= html_writer::start_tag('div');

        foreach ($idstoslots as $displaynumber => $slot) {
            $display .= $quba->render_question($slot, $options, $displaynumber);
        }


        $display .= html_writer::start_tag('div');

        $display .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'next',
            'value' => 'Submit', 'class' => 'mod_quiz-next-nav btn btn-primary'));
        $display .= html_writer::end_tag('div');

        $display .= html_writer::end_tag('div');
        $display .= html_writer::end_tag('form');

        return $display;
    }


    public function render_main_page($gmcompcpu, $userid)
        {
            global $DB;
            $userid = $DB->get_record('gmdl_usuario', $conditions=array("mdl_id_usuario" => $userid), $fields='*', $strictness=IGNORE_MISSING)->id;
            $dificultades = array_values($DB->get_records($table='gmdl_dificultad_cpu', $conditions=null, $sort='id', $fields='*', $limitfrom=0, $limitnum=0));
            $sql = " SELECT gmdl_dificultad_cpu_id, COUNT(id)  FROM {gmdl_intento}";
            $sql.= " WHERE puntuacion_usuario > puntuacion_cpu";
            $sql.= " AND gmdl_usuario_id = $userid";
            $sql.= " AND gmdlcompcpu_id = ".$gmcompcpu->id;
            $sql.= " GROUP BY 1;";
            $victorias = $DB->get_records_sql($sql, null, $limitfrom=0, $limitnum=0);
            $compusVencidas = '';
            $nombresDificultades = '';
            $valoresSelect = '';
            foreach($dificultades as $dificultad) 
                {
                    $nombresDificultades.= "<th>".$dificultad->nombre."</th>";
                    $noEncontrado = TRUE;
                    foreach($victorias as $victoria)
                        {
                            if($victoria->gmdl_dificultad_cpu_id == $dificultad->id && $noEncontrado)
                                {
                                    $compusVencidas.= "<td class='cgmcompcpu-cpu-vencida'> Sí </td>";
                                    $noEncontrado = FALSE;
                                }
                        }
                    if($noEncontrado)
                        {
                            $compusVencidas.= "<td class='cgmcompcpu-cpu'> No </td>";
                        }
                    $valoresSelect.="<option value='".$dificultad->id."'> ".$dificultad->nombre."</option>";
                }


            $html = "<link href='styles.css' rel='stylesheet' type='text/css'>";


            $html.=" <div class='gmcompcpu-linea'> </div> <h1 class='gmcompcpu-titulo'> Computadoras vencidas </h1>";
            $html.= "<div class='gmcompcpu-container'>  <table> <thead> <tr> $nombresDificultades </tr> </thead>";
            $html.=" <tbody> <tr> $compusVencidas </tr> </tbody> </table> </div>";
            $html.=" <div class='gmcompcpu-container'> <div class='gmcompcpu-container-card'> <h3 class='gmcompcpu-container-card-title'> <b>Desafiar computadora</b> </h3>";
            $html.="   <div class='gmcompcpu-card-element'> Seleccione dificultad: <select> $valoresSelect </select> </div>";
            $html.="  <div class='gmcompcpu-card-element'> <button type='button' class='btn btn-primary gmcompcpu-container-card-button'> Empezar</button> </div> </div> </div>";
            $html.=" <div class='gmcompcpu-linea'> </div>";
            //return json_encode($gmcompcpu);
            return $html.$this->render_scores_page($gmcompcpu, $userid);
            //return json_encode(array_values($dificultades));
        }


    public function render_scores_page($gmcompcpu, $userid)
        {
            global $DB;
            $dificultades = array_values($DB->get_records($table='gmdl_dificultad_cpu', $conditions=null, $sort='id', $fields='*', $limitfrom=0, $limitnum=0));
            $leaderboards = [];
            $leaderboardsMax = [];
            for($i=0; $i<sizeof($dificultades);$i++)
                {
                    $leaderboards[] = array();
                    $leaderboardsMax[] = array();
                }
            $sql = "SELECT b.* FROM";
		    $sql.= " (";
			$sql.= " SELECT gmdl_dificultad_cpu_id, gmdl_usuario_id, MIN(fecha_fin) as minima";
			$sql.= " FROM {gmdl_intento} ";
			$sql.= " WHERE fecha_fin IS NOT NULL";
			$sql.= " AND gmdlcompcpu_id = ".$gmcompcpu->id;;
            $sql.= " GROUP BY 1, 2";
		    $sql.= " ) AS a";
            $sql.= " JOIN";
            $sql.= " (";
            $sql.= " SELECT gmdl_usuario_id, gmdl_dificultad_cpu_id, puntuacion_usuario AS puntos, fecha_fin, username, firstname, lastname";
            $sql.= " FROM {gmdl_intento}, {user}";
            $sql.= " WHERE gmdlcompcpu_id = ".$gmcompcpu->id." AND";
            $sql.= " {user}.id = {gmdl_intento}.gmdl_usuario_id";
            $sql.= " ) AS b";
            $sql.="  ON";
			$sql.= " a.gmdl_usuario_id = b.gmdl_usuario_id AND";
			$sql.= " a.gmdl_dificultad_cpu_id = b.gmdl_dificultad_cpu_id AND";
			$sql.= " a.minima = b.fecha_fin";
            $sql.= " ORDER BY b.puntos DESC";
            $primerosIntentos = $DB->get_records_sql($sql, null, $limitfrom=0, $limitnum=0);
            foreach($primerosIntentos as $intento)
                {
                    $leaderboards[$intento->gmdl_dificultad_cpu_id-1][] = $intento;
                }

            $sql =" SELECT a.*, username, firstname, lastname FROM";
            $sql.=" (SELECT gmdl_dificultad_cpu_id, gmdl_usuario_id, MAX(puntuacion_usuario) AS puntos";
            $sql.=" FROM {gmdl_intento}";
            $sql.=" WHERE gmdlcompcpu_id = ".$gmcompcpu->id;
            $sql.=" GROUP BY 1,2 ) as a, {user}";
            $sql.=" WHERE {user}.id = a.gmdl_usuario_id";
            $sql.=" ORDER BY a.puntos DESC";
            $mejoresIntentos = $primerosIntentos = $DB->get_records_sql($sql, null, $limitfrom=0, $limitnum=0);
            foreach($mejoresIntentos as $intento)
                {
                    $leaderboardsMax[$intento->gmdl_dificultad_cpu_id-1][] = $intento;
                }

            $html = '';
            #$html = "<link href='styles.css' rel='stylesheet' type='text/css'>";
            $html.="<div class='gmcompcpu-linea'> </div>";
            $i = 0;
            foreach($dificultades as $dificultad)
                {
                    $leaderboard = $leaderboards[$i];
                    $leaderboardMax = $leaderboardsMax[$i];
                    $html.="<div class='gmcompcpu-linea-nivel-".$dificultad->id."'> ".$dificultad->nombre." </div>";
                    $html.="<div class='gmcompcpu-container'>";
                    $cabezeraTabla = "<thead> <tr> <th> Posici&oacute;n </th> <th> Nombre </th> <th> Puntuaci&oacute;n </th> </thead>";
                    $j=1;
                    $contenidoTablaPrimerIntento= '<tbody>';
                    foreach($leaderboard as $intento)
                        {
                            if($j==1)
                                {
                                    $contenidoTablaPrimerIntento= "<tr class='gmcompcpu-primer-lugar'> <td>".$j."° </td> <td> ".$intento->firstname." ".$intento->lastname."</td> <td>".$intento->puntos."</td> </tr>";
                                }
                            else if($j==2)
                                {
                                    $contenidoTablaPrimerIntento= "<tr class='gmcompcpu-segundo-lugar'> <td>".$j."° </td> <td> ".$intento->firstname." ".$intento->lastname."</td> <td>".$intento->puntos."</td> </tr>";
                                }
                            else if($j ==3)
                                {
                                    $contenidoTablaPrimerIntento= "<tr class='gmcompcpu-tercer-lugar'> <td>".$j."° </td> <td> ".$intento->firstname." ".$intento->lastname."</td> <td>".$intento->puntos."</td> </tr>";
                                }
                            else
                                {
                                    $contenidoTablaPrimerIntento= "<tr class='gmcompcpu-n-lugar'> <td>".$j."° </td> <td> ".$intento->firstname." ".$intento->lastname."</td> <td>".$intento->puntos."</td> </tr>";
                                }
                            $j++;
                        }
                    if($j == 1)
                        {
                            $contenidoTablaPrimerIntento.=  "<tr class='gmcompcpu-no-lugar'> <td> 0° </td> <td> Sin participantes </td>  <td> - - - </td> </tr>";
                        }
                    $contenidoTablaPrimerIntento.= '</tbody>';
                    $html.= "<div class='gmcompcpu-half-container'> <table>".$cabezeraTabla.$contenidoTablaPrimerIntento."</table></div>";
                    $j=1;
                    $contenidoTablaMejorIntento= '<tbody>';
                    foreach($leaderboardMax as $intento)
                        {
                            if($j==1)
                                {
                                    $contenidoTablaMejorIntento= "<tr class='gmcompcpu-primer-lugar'> <td>".$j."° </td> <td> ".$intento->firstname." ".$intento->lastname."</td> <td>".$intento->puntos."</td> </tr>";
                                }
                            else if($j==2)
                                {
                                    $contenidoTablaMejorIntento= "<tr class='gmcompcpu-segundo-lugar'> <td>".$j."° </td> <td> ".$intento->firstname." ".$intento->lastname."</td> <td>".$intento->puntos."</td> </tr>";
                                }
                            else if($j ==3)
                                {
                                    $contenidoTablaMejorIntento= "<tr class='gmcompcpu-tercer-lugar'> <td>".$j."° </td> <td> ".$intento->firstname." ".$intento->lastname."</td> <td>".$intento->puntos."</td> </tr>";
                                }
                            else
                                {
                                    $contenidoTablaMejorIntento= "<tr class='gmcompcpu-n-lugar'> <td>".$j."° </td> <td> ".$intento->firstname." ".$intento->lastname."</td> <td>".$intento->puntos."</td> </tr>";
                                }
                            $j++;
                        }
                    if($j == 1)
                        {
                            $contenidoTablaMejorIntento.= "<tr class='gmcompcpu-no-lugar'> <td> 0° </td> <td> Sin participantes </td>  <td> - - - </td> </tr>";
                        }
                    $contenidoTablaMejorIntento.= '</tbody>';
                    $html.= "<div class='gmcompcpu-half-container'> <table>".$cabezeraTabla.$contenidoTablaMejorIntento."</table></div>";
                    $html.="</div>";
                    $html.="<div class='gmcompcpu-linea'> </div>";
                    $i++;
                }
            return $html.$this->render_attempt_page($gmcompcpu, $userid);
        }
    public function render_attempt_page($gmcompcpu, $userid)
        {
            $html = ' ';
            #$html = "<link href='styles.css' rel='stylesheet' type='text/css'>";
            global $DB;
            $dificultades = array_values($DB->get_records($table='gmdl_dificultad_cpu', $conditions=null, $sort='id', $fields='*', $limitfrom=0, $limitnum=0));
            
            $html.="<div class='gmcompcpu-linea'> </div>";
            $intentos = array_values($DB->get_records($table='gmdl_intento', $conditions=array("gmdlcompcpu_id" =>$gmcompcpu->id, "gmdl_usuario_id"=> $userid ), $sort='gmdl_dificultad_cpu_id, puntuacion_usuario', $fields='*', $limitfrom=0, $limitnum=0));
            $html.="<h1 class='gmcompcpu-titulo'> Intentos realizados </h1>";
            $html.="<div class='gmcompcpu-container'>";
            $cabezeraTabla = "<thead> <tr> <th> Dificultad </th> <th> Puntos obtenidos </th> <th> Puntos computadora </th>  </tr> </thead>";
            $contenidoTabla = "<tbody>";
            
            foreach($intentos as $intento)
                {
                    $contenidoTabla.= "<tr>";
                    $contenidoTabla.= "<td class='gmcompcpu-tabla-celda-nivel-".$dificultades[$intento->gmdl_dificultad_cpu_id]->id."'> ".$dificultades[$intento->gmdl_dificultad_cpu_id]->nombre." </td>";
                    if($intento->puntuacion_usuario >= $intento->puntuacion_cump)
                        {
                            $contenidoTabla.= "<td> ".$intento->puntuacion_usuario. "</td>";
                            $contenidoTabla.= "<td> ".$intento->puntuacion_cpu . "</td>";
                        }
                    else
                        {
                            $contenidoTabla.= "<td class='gmcompcpu-tabla-celda-ganadora'> ".$intento->puntuacion_usuario. "</td>";
                            $contenidoTabla.= "<td> ".$intento->puntuacion_cpu . "</td>";
                        }
                    $contenidoTabla.= "</tr>";
                }
            if(sizeof($intentos) == 0)
                {
                    $contenidoTabla.= "<tr class='gmcompcpu-no-lugar'> <td> Sin intentos </td> <td> - - - </td> <td> - - - </td> </tr>";
                }
            $contenidoTabla.= "</tbody>";
            $html.= "<table>".$cabezeraTabla.$contenidoTabla."</table></div>";
            return $html;
        }
    /**
     * Render the link to access the high scores.
     * @param stdClass $gmcompcpu
     * @return string
     */
    public function render_score_link($gmcompcpu) {

        $url = new moodle_url('/mod/gmcompcpu/scores.php', array('id' => $gmcompcpu->id));
        $scorestring = get_string('scoreslink', 'gmcompcpu');
        $scorestringhelp = get_string('scoreslinkhelp', 'gmcompcpu');
        $display = html_writer::start_tag('div', array('class' => 'gmcompcpu-scores'));
        $display .= html_writer::tag('a', $scorestring, array('title' => $scorestringhelp, 'href' => $url));
        $display .= html_writer::end_tag('div');
        return $display;
    }
}
