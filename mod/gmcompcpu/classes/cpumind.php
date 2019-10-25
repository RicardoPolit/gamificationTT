<?php

class mod_gmcompcpu__cpumind {



    static function cpuattempt( $quba , $cm, $dificultad){

        /*$gmcompcpu  = $DB->get_record('gmcompcpu', array('id' => $cm->instance), '*', MUST_EXIST);

        $difficulty = $gmcompcpu->cpudifficulty;*/

        $slots = $quba->get_slots();

        foreach ($slots as $i => $slot){

            $questionids[$i] = $quba->get_question($slot)->id;

        }

        $todasrespuestas = [];

        foreach ($questionids as $questionid) {

            $w = self::respuestasTotales($questionid);
            $x = self::obtenerNumResCorrectas($w);
            $y = self::obtenerNumResElegidas($x, $dificultad, $questionid);


            $intentosDisponibles = self::obtenerNumeroIntentos($dificultad);

            $espacio = self::obtenerEspacioMuestral($dificultad);
            $respuestasElegidas = [];
            $w = self::ponderarRespuestas($x,$dificultad, $w);

            while (sizeof($respuestasElegidas) < $y) {

                $resp = self::responderAlAzar($w);

                if(!self::esCorrecta($resp)) {

                    if($intentosDisponibles == 0) {

                        $respuestasElegidas[] = $resp;

                    }else{

                        $intentosDisponibles --;

                    }
                } else {

                    $respuestasElegidas[] = $resp;

                }

                $lenW = sizeof($w);
                $w = self::quitarRespuesta($resp, $w, $espacio);

                if( $lenW > sizeof($w)) {

                    $w = self::ponderarRespuestas($x,$dificultad, $w);

                }

            }

            $todasrespuestas[$questionid] = $respuestasElegidas;

        }

        return $todasrespuestas;

    }

     protected static function processAnswer( $dbanswer ){

        $answer = [];

        foreach ( $dbanswer as $i => $rows ){

            $answer[$i] = new stdClass();
            $answer[$i]->id = $rows->id;
            $answer[$i]->fraction = $rows->fraction;
            $answer[$i]->answer = $rows->answer;
            $answer[$i]->weighing = 1;
            $answer[$i]->index = $i;
            $answer[$i]->question = $rows->question;

        }

        return $answer;

    }

    protected static function respuestasTotales( $questionid ){

        global $DB;

        $answers = self::processAnswer($DB->get_records('question_answers', array('question' => $questionid)));

        return $answers;

    }

    protected static function obtenerNumResCorrectas($w){

        $numResCorrectas = 0;

        foreach ($w as $answer){

            if( $answer->fraction > 0 ){

                $numResCorrectas++;

            }

        }

        return $numResCorrectas;

    }

    protected static function obtenerNumResElegidas($x, $dificultad, $questionid){

        $rows = question_preload_questions(array($questionid));
        /*$rows = $DB->get_records('question', array('id' => $questionid));*/

        if($rows[0]->qtype != 'multichoice'){

            return 1;

        }else{

            if($rows[0]->options->single == 1 ){

                return 1;

            }else{

                $numrand = rand(0, 100);

                if( $dificultad == 1 ){

                    if( $numrand <= 5 ){

                        return $x;

                    }elseif ( $numrand <= 15 ){

                        return (int)$x/2;

                    }else{

                        return 1;

                    }

                }elseif( $dificultad == 2 ){

                    if( $numrand <= 15 ){

                        return $x;

                    }elseif ( $numrand <= 35 ){

                        return (int)$x/2;

                    }else{

                        return 1;

                    }

                }elseif( $dificultad == 3 ){

                    if( $numrand <= 65 ){

                        return $x;

                    }elseif ( $numrand <= 85 ){

                        return (int)$x/2;

                    }else{

                        return 1;

                    }

                }else{

                    if( $numrand <= 85 ){

                        return $x;

                    }elseif ( $numrand <= 95 ){

                        return (int)$x/2;

                    }else{

                        return 1;

                    }

                }



            }

        }

    }

    protected static function obtenerNumeroIntentos($dificultad){

        return $dificultad;

    }

    protected static function obtenerEspacioMuestral($dificultad){

        /*return 25*$dificultad;*/

        return 20+(10*$dificultad);

    }

    protected static function ponderarRespuestas($x,$dificultad, $w){

        if( $dificultad <= 2 ){

            $valorQuitado = $x*((2-$dificultad)*5);

        }else{

            $valorQuitado = (sizeof($w)-$x)*(($dificultad-2)*5);

        }

        foreach ($w as $i => $answer){

            $answer->weighing = 100/sizeof($w);

            if( $dificultad <= 2 ){

                if($answer->fraction > 0){

                    $answer->weighing -= (2-$dificultad)*5;

                }else{

                    $answer->weighing += $valorQuitado/(sizeof($w)-$x);

                }

            }else{

                if($answer->fraction > 0){

                    $answer->weighing += $valorQuitado/$x;

                }else{

                    $answer->weighing -= ($dificultad-2)*5;

                }

            }

            $w[$i] = $answer;

        }

        return $w;

    }

    protected static function responderAlAzar($w){

        /*return $w[rand(0,sizeof($w)-1)];*/

        $numeroElegido = rand(0,100);
        $cantidadAcomulada = 0;
        $currentAnswer = $w[rand(0,sizeof($w)-1)];

        foreach ($w as $answer){

            if( $numeroElegido >= $cantidadAcomulada && $numeroElegido < ($cantidadAcomulada + $answer->weighing) ){

                $currentAnswer = $answer;


            }

            $cantidadAcomulada += $answer->weighing;

        }

        return $currentAnswer;

    }

    protected static function esCorrecta($resp){

        return ($resp->fraction == 1);

    }

    //Revisar cuando ya no se puedan quitar las respuestas y todavia falte por elegir respuestas, existirá el caso de que se elija la misma respuesta?

    protected static function quitarRespuesta($resp, $w, $espacio){

        if( $espacio >= 100/(sizeof($w)-1) ){

            unset($w[$resp->index]);

        }

        return $w;

    }

}


