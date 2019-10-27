<?php

//TODO agregar que el defaultmark de cada pregunta tenga que ver con el 100 cuando se calcula el puntaje
//TODO revisar base de datos y ahorita las consultaas que te mando david para ver como piensa hacer lo de los intentos

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/mod/gmcompcpu/classes/cpumind.php');

$fechafin = date('Y-m-d H:i:s');

$id  = optional_param('id', "", PARAM_INT);  // Is the param action.
$cm  = optional_param('cm', "", PARAM_INT);  // Is the param action.
$userid = optional_param('userid', "", PARAM_INT);  // Is the param action.
$intentoid = optional_param('intentoid', "", PARAM_INT);  // Is the param action.
$idredirect  = optional_param('idredirect', "", PARAM_INT);  // Is the param action.
$intento = $DB->get_record('gmdl_intento', array('id' => $intentoid), '*', MUST_EXIST);

$timenow = time();

$quba = question_engine::load_questions_usage_by_activity($id);

$userScore = calculateScoreUser($quba,$timenow);

/*for ($i = 1; $i <= 4; $i++) {                                     //Pruebas para ver como se comportan las diferentes dificultades (no estan funcionando como se espera)

    $scoreAv = 0;

    $scorePercents = [];

    for ($y = 0; $y <= 10; $y++) {

        $scorePercents[$y] = 0;

    }

    for ($y = 0; $y < 10000; $y++) {

        $questionswithAnswers = mod_gmcompcpu__cpumind::cpuattempt($quba,$cm,$i);
        $currentScore = calculateScoreCpu($questionswithAnswers);

        $scoreAv += $currentScore;

        $scorePercents[(int)($currentScore/50)]++;

    }

    for ($y = 0; $y <= 10; $y++) {

        $scorePercents[$y] = ($scorePercents[$y]/10000)*100;

    }

    $scoreAv = $scoreAv/10000;

    echo '<br>';
    echo '---------------------------';
    echo '<br>';
    echo 'Promedio de puntaje en nivel: '.$i.': '.$scoreAv;
    foreach ( $scorePercents as $x => $scorePercent ){

        echo '<br>';
        echo 'Probabilidad de puntuación de '.$x.': '.$scorePercent.'%';

    }

}*/

$questionswithAnswers = mod_gmcompcpu__cpumind::cpuattempt($quba,$cm,$intento->gmdl_dificultad_cpu_id);

/*echo json_encode($questionswithAnswers);
echo '<br>';*/

$cpuScore = calculateScoreCpu( $questionswithAnswers );

$values = (object)[
    'puntuacion_cpu' => $cpuScore,
    'puntuacion_usuario' => $userScore,
    'fecha_fin' => time()
];

$values->id = $intentoid;

$DB->update_record('gmdl_intento',$values);

insertCpuAnswers($questionswithAnswers,$intentoid);

$urltogo = new moodle_url('/mod/gmcompcpu/view.php', array('id' => $idredirect));

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

function calculateScoreUser($quba,$timenow){

    global $DB;

    $score = 0;

    $quba->process_all_actions($timenow);

    //Itera entre todas las preguntas del intento

    foreach ($quba->get_slots() as $slot){
        /*echo '<br>';*/
        $qa = $quba->get_question_attempt($slot);
        $dbanswers = $DB->get_records('question_answers', array('question' => $qa->get_question()->id));
        $answers = processAnswer($dbanswers);
        /*echo $qa->get_question()->id;*/
        /*echo '  '.$qa->get_question()->get_type_name();*/
        /*echo '<br>';*/

        //Se trata de manera diferente las preguntas tipo multichoice

        if($qa->get_question()->get_type_name() == 'multichoice'){

            $order = explode(',',$qa->get_step_iterator()[0]->get_all_data()['_order']);
            $useranswers = explode(',',$qa->get_step_iterator()[1]->get_all_data()['answer']);

            foreach ($useranswers as $useranswer){

                foreach ($answers as $answer){

                    if($order[$useranswer] == $answer->id){

                        $score += 100 * $answer->fraction;

                    }

                    /*echo 'Esto es getrecords: id: '.$answer->id.' fraction: '.$answer->fraction.' answer: '.$answer->answer;*/
                    /*echo '<br>';*/
                }

            }



            /*echo json_encode($order);*/
            /*echo '<br>';*/
            /*echo json_encode($useranswers);*/

        }else{

            $useranswer = $qa->get_step_iterator()[1]->get_all_data()['answer'];

            if($qa->get_question()->get_type_name() == 'truefalse'){

                if($useranswer){

                    $useranswer = 'true';

                }else{

                    $useranswer = 'false';

                }

            }


            foreach ($answers as $answer){

                /*echo 'useranswer: '.strtolower($useranswer).' answer: '.strtolower($answer->answer).' comparacion: ';
                echo strtolower($useranswer) == strtolower($answer->answer);
                echo '<br>';*/

                if(strtolower($useranswer) == strtolower($answer->answer)){

                    $score += 100 * $answer->fraction;

                }

                /*echo 'Esto es getrecords: id: '.$answer->id.' fraction: '.$answer->fraction.' answer: '.$answer->answer;*/
                /*echo '<br>';*/
            }

            /*echo '<br>';*/
            /*echo json_encode($useranswer);*/

        }


        /*echo '<br>';*/
        /*echo $score;*/

    }

    return $score;

}