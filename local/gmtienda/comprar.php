<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

require_once('../../config.php');

$gmuserid = optional_param('gmuserid', 0, PARAM_INT);
$objetoid = optional_param('objetoid', 0, PARAM_INT);


$course     = $DB->get_record('course', array('id' => 1), '*', MUST_EXIST);
$title = get_string('perfilgamificado', 'local_gmtienda');
$pagetitle = $title;
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/gmtienda/perfilgamificado.php');
require_login($course,true);
$PAGE->set_title($title);
$PAGE->set_heading('Procesando compra');
$renderer = $PAGE->get_renderer('local_gmtienda');

global $DB;

$usuariogamificado = $DB->get_record('gmdl_usuario',array('id' => $gmuserid));

$objetocomprado = 0;

if( $usuariogamificado->mdl_id_usuario == $USER->id ){

    $objeto = $DB->get_record('gmdl_objeto',array('id' => $objetoid));
    $rarezaobjeto = $DB->get_record('gmdl_rareza_objeto',array('id' => $objeto->gmdl_rareza_objeto_id));

    if( $usuariogamificado->monedas_plata >= $rarezaobjeto->costo_adquisicion ){

        $objetocomprado = -1;

        $nodesbloqueado = $DB->count_records('gmdl_objeto_desbloqueado',array( 'gmdl_objeto_id' => $objetoid, 'gmdl_usuario_id' => $gmuserid )) == 0;

        if($nodesbloqueado){

            $objetodesbloqueado = (object)[
                'gmdl_objeto_id' => $objetoid,
                'gmdl_usuario_id' => $gmuserid
            ];

            $iddesbloqueado = $DB->insert_record('gmdl_objeto_desbloqueado',$objetodesbloqueado);


            if( $iddesbloqueado != 0 && !is_null($iddesbloqueado) && $iddesbloqueado ){

                $objetocomprado = 1;

                $usuariogamificado->monedas_plata -= $rarezaobjeto->costo_adquisicion;

                $DB->update_record('gmdl_usuario',$usuariogamificado);

            }else{

                $objetocomprado = -2;

            }

        }

    }

    echo $OUTPUT->header();

    echo $renderer->render_buy_answer($objeto,$objetocomprado);

    echo $OUTPUT->footer();

}else{

    echo "El valor de gmuserid no corresponde a tu usuario actual, te caché";

}

