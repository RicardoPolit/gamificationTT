<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/mod/gmcompcpu/classes/cpumind.php');
require_once($CFG->dirroot.'/lib/completionlib.php');

$id  = optional_param('id', "", PARAM_INT);  // Is the param action.
$cm  = optional_param('cm', "", PARAM_INT);  // Is the param action.
$userid = optional_param('userid', "", PARAM_INT);  // Is the param action.
$intentoid = optional_param('intentoid', "", PARAM_INT);  // Is the param action.
$idredirect  = optional_param('idredirect', "", PARAM_INT);  // Is the param action.
$intento = $DB->get_record('gmdl_intento', array('id' => $intentoid), '*', MUST_EXIST);

$timenow = time();

$quba = question_engine::load_questions_usage_by_activity($id);

$userScore = calculateScoreUser($quba,$timenow);

/*echo json_encode($userScore);*/

/*for ($i = 1; $i <= 4; $i++) {                                     //Pruebas para ver como se comportan las diferentes dificultades (funciona como se espera)

    $scoreAv = 0;

    $scorePercents = [];

    for ($y = 0; $y <= 10; $y++) {

        $scorePercents[$y] = 0;

    }

    for ($y = 0; $y < 10000; $y++) {

        $questionswithAnswers = mod_gmcompcpu__cpumind::cpuattempt($quba,$cm,$i);
        $currentScore = calculateScoreCpu($questionswithAnswers);

        $scoreAv += (int)($currentScore/60);

        $scores[] = (int)($currentScore/60);

        $scorePercents[(int)($currentScore/60)]++;

    }

    for ($y = 0; $y <= 10; $y++) {

        $scorePercents[$y] = ($scorePercents[$y]/10000)*100;

    }

    $scoreAv = $scoreAv/10000;
    $sumasuperior = 0;
    foreach ($scores as $score){

        $sumasuperior += pow($score-$scoreAv,2);

    }

    $desviacionestandar = sqrt($sumasuperior/10000);

    echo '<br>';
    echo '---------------------------';
    echo '<br>';
    echo 'Desviación estandar en nivel: '.$i.': '.$desviacionestandar;
    echo '<br>';
    echo 'Promedio de puntaje en nivel: '.$i.': '.$scoreAv;
    foreach ( $scorePercents as $x => $scorePercent ){

        echo '<br>';
        echo 'Probabilidad de puntuación de '.$x.': '.$scorePercent.'%';

    }


}*/

$questionswithAnswers = mod_gmcompcpu__cpumind::cpuattempt($quba,$cm,$intento->gmdl_dificultad_cpu_id);        //ESTA

/*echo json_encode($questionswithAnswers);
echo '<br>';*/

$cpuScore = calculateScoreCpu( $questionswithAnswers );

$gmcompcpu  = $DB->get_record('gmcompcpu', array('id' => $cm), '*', MUST_EXIST);
$course     = $DB->get_record('course', array('id' => $gmcompcpu->course), '*', MUST_EXIST);
$cm         = get_coursemodule_from_instance('gmcompcpu', $gmcompcpu->id, $course->id, false, MUST_EXIST);

insertCpuAnswers($questionswithAnswers,$intentoid);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);


$PAGE->set_title(format_string($gmcompcpu->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

$renderer = $PAGE->get_renderer('mod_gmcompcpu');

echo $OUTPUT->header();

echo $OUTPUT->heading($gmcompcpu->name);


echo $renderer->render_results_attempt($userScore, $cpuScore, $cm->id);

echo $OUTPUT->footer();

$maxScore = 0;

$maxScore = $DB->get_record('gmdl_intento',array('gmdl_dificultad_cpu_id' => $intento->gmdl_dificultad_cpu_id, 'gmdl_usuario_id' => $intento->gmdl_usuario_id, 'gmdlcompcpu_id' => $gmcompcpu->id),'MAX(puntuacion_usuario) as puntos')->puntos;

$where = ' gmdlcompcpu_id = :gmcompcpuid AND gmdl_dificultad_cpu_id = :cpudificultad AND gmdl_usuario_id = :gmuserid AND puntuacion_usuario >= puntuacion_cpu';
$params = array(
    'gmcompcpuid' => $gmcompcpu->id,
    'gmuserid' =>  $intento->gmdl_usuario_id,
    'cpudificultad' => $intento->gmdl_dificultad_cpu_id,
);
$defeatedcpu = $DB->count_records_select('gmdl_intento', $where, $params) == 0;

if(($userScore > $maxScore || $defeatedcpu) && $userScore >= $cpuScore){

    $event = \local_gamedlemaster\event\gmcompcpu_compFinishedWon::create(array(
        'objectid' => $gmcompcpu->id,
        'context' => $context,
        'other' => array('userid' => $intento->gmdl_usuario_id, 'dificultad' => $intento->gmdl_dificultad_cpu_id),
    ));

    $event->add_record_snapshot('gmcompcpu', $gmcompcpu);
    $event->trigger();

}

$values = (object)[
    'puntuacion_cpu' => $cpuScore,
    'puntuacion_usuario' => $userScore,
    'fecha_fin' => $timenow
];

$values->id = $intentoid;

$DB->update_record('gmdl_intento',$values);

if( $userScore >= $cpuScore ){

    $completion = new completion_info($course);
    if($completion->is_enabled($cm) && $gmcompcpu->completioncpudiff != 0 && $gmcompcpu->completioncpudiff != NULL ) {
        $completion->update_state($cm,COMPLETION_COMPLETE,$userid);
    }

}

/*$urltogo = new moodle_url('/mod/gmcompcpu/view.php', array('id' => $idredirect));

redirect($urltogo);

/*echo $userScore;
echo '<br>';
echo $cpuScore;*/

function insertCpuAnswers( $questionswithAnswers ,$intentoid){

    global $DB;

    foreach ($questionswithAnswers as $questionwithAnswers){

        foreach ( $questionwithAnswers as $answer ){

            $values = (object)array('mdl_question_id' => $answer->question, 'mdl_question_answers_id' => $answer->id, 'gmdl_intento_id' => $intentoid);

            $DB->insert_record('gmdl_respuesta_cpu',$values);

        }

    }

}

function processAnswer( $dbanswer ){

    foreach ( $dbanswer as $i => $rows ){

        $answer[$i] = new stdClass();
        $answer[$i]->id = $rows->id;
        $answer[$i]->fraction = $rows->fraction;
        $answer[$i]->answer = $rows->answer;

    }

    return $answer;

}

function calculateScoreCpu( $questionswithAnswers ){

    $score = 0;

    foreach ($questionswithAnswers as $questionwithAnswers){

        foreach ( $questionwithAnswers as $answer ){

            $score += (100) * $answer->fraction;

        }

    }

    return $score;

}

function calculateScoreUser($quba, $timenow){

    global $DB;

    $score = 0;

    $quba->process_all_actions($timenow);

    //Itera entre todas las preguntas del intento

    foreach ($quba->get_slots() as $slot){
        $qa = $quba->get_question_attempt($slot);
        $dbanswers = $DB->get_records('question_answers', array('question' => $qa->get_question()->id));
        $question = $DB->get_record('question', array('id' => $qa->get_question()->id));
        $answers = processAnswer($dbanswers);

        //Se trata de manera diferente las preguntas tipo multichoice

        if($qa->get_question()->get_type_name() == 'multichoice')
            {
                try
                    {
                        $single = $DB->get_record('qtype_multichoice_options',array('questionid' => $qa->get_question()->id ),'single');
                        $order = explode(',',$qa->get_step_iterator()[0]->get_all_data()['_order']);

                        if($single->single == 1)
                            {
                                $useranswers = explode(',',$qa->get_step_iterator()[1]->get_all_data()['answer']);
                            }
                        else
                            {
                                $useranswers = [];
                                $useranswersAux = $qa->get_step_iterator()[1]->get_all_data();
                                $i = 0;
                                foreach ($useranswersAux as $useranswer)
                                    {
                                        if($useranswer == 1)
                                            {
                                                $useranswers[] = $i;
                                            }
                                        $i++;
                                    }
                            }
                    }
                catch (Exception $e)
                    {
                        $useranswers = null;
                    }

                if($useranswers != null){
                    foreach ($useranswers as $useranswer){
                        foreach ($answers as $answer){
                            if($order[$useranswer] == $answer->id){
                                $score += (100*$question->defaultmark) * $answer->fraction;
                            }
                        }
                    }
                }
            }
        else if($qa->get_question()->get_type_name() == 'truefalse')
            {

                try
                    {
                        $useranswer = $qa->get_step_iterator()[1]->get_all_data()['answer'];
                    }
                catch (Exception $e)
                    {
                        $useranswer = null;
                    }

                if(!($useranswer === null))
                    {
                        if($useranswer)
                            {
                                $useranswer = 0;
                            }
                        else
                            {
                                $useranswer = 1;
                            }
                        $answers = array_values($answers);
                        $score += (100*$question->defaultmark) * $answers[$useranswer]->fraction;
                    }
            }
        else
            {
                try
                    {
                        $useranswer = $qa->get_step_iterator()[1]->get_all_data()['answer'];
                    }
                catch (Exception $e)
                    {
                        $useranswer = null;
                    }

                if(!($useranswer === null))
                    {


                        foreach ($answers as $answer) {

                            /*echo 'useranswer: '.strtolower($useranswer).' answer: '.strtolower($answer->answer).' comparacion: ';
                            echo strtolower($useranswer) == strtolower($answer->answer);
                            echo '<br>';*/

                            if (strtolower($useranswer) == strtolower($answer->answer)) {

                                $score += (100*$question->defaultmark) * $answer->fraction;

                            }

                        }
                    }
            }
    }
    return $score;

}
