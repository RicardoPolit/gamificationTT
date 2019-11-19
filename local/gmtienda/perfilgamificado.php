<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

require_once('../../config.php');

$gmuserid = optional_param('gmuserid', 0, PARAM_INT);
$objetoid = optional_param('objetoid', 0, PARAM_INT);
$objetosjson = optional_param('objetosjson',0,PARAM_RAW);

$course     = $DB->get_record('course', array('id' => 1), '*', MUST_EXIST);
$title = get_string('perfilgamificado', 'local_gmtienda');
$pagetitle = $title;
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/gmtienda/perfilgamificado.php');
require_login($course,true);
$PAGE->set_title($title);
$PAGE->set_heading($title);
$renderer = $PAGE->get_renderer('local_gmtienda');

$mensaje_peticion = procesar_peticion( $gmuserid, $objetoid, $objetosjson, $USER );

global $DB;

$usuariogamificado = obtener_usuario_gamedle($USER->id);
if(is_null($usuariogamificado))
    {
        $usuariogamificado = insertar_usuario($USER->id);
    }

echo $OUTPUT->header();

echo $renderer->render_main_page($usuariogamificado, $USER->id, $mensaje_peticion);

echo $OUTPUT->footer();



function procesar_peticion( $gmuserid, $objetoid, $objetosjson, $USER ){

    if( $gmuserid != 0 ){

        if( $objetoid != 0 && $objetosjson == 0 ){

            return procesar_compra( $gmuserid, $objetoid, $USER );

        }else if( $objetoid == 0 && !empty($objetosjson) ){

            return procesar_eleccion( $gmuserid, $objetosjson, $USER );

        }

    }

    return null;

}

function procesar_compra( $gmuserid, $objetoid, $USER ){

    $respuesta = new stdClass();
    $respuesta->mensaje = 'OH NO! compra';
    $respuesta->tipo =  \core\output\notification::NOTIFY_ERROR;

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

        return obtener_mensaje_compra( $objeto, $objetocomprado );

    }else{

        $respuesta->mensaje = "El valor de gmuserid no corresponde a tu usuario actual, te caché";
        $respuesta->tipo =  \core\output\notification::NOTIFY_ERROR;

    }

    return $respuesta;

}

function obtener_mensaje_compra( $objeto, $objetocomprado ){

    $respuesta = new stdClass();
    $respuesta->mensaje = 'OH NO! compra';
    $respuesta->tipo =  \core\output\notification::NOTIFY_ERROR;

    if($objetocomprado == 1){

        $respuesta->mensaje = "¡Objeto $objeto->nombre comprado!";
        $respuesta->tipo = \core\output\notification::NOTIFY_SUCCESS;

    }elseif ( $objetocomprado == 0 ){

        $respuesta->mensaje = "¡Oh no!, No tienes suficiente dinero para comprar el objeto $objeto->nombre!";
        $respuesta->tipo = \core\output\notification::NOTIFY_ERROR;

    }elseif ( $objetocomprado == -1 ){

        $respuesta->mensaje = "¡Ya tenías desbloqueado el objeto $objeto->nombre!";
        $respuesta->tipo = \core\output\notification::NOTIFY_INFO;

    }else{

        $respuesta->mensaje = "¡Esto no debería de pasar nunca!, intenta más tarde";
        $respuesta->tipo = \core\output\notification::NOTIFY_ERROR;

    }

    return $respuesta;

}

function procesar_eleccion( $gmuserid, $objetosjson, $USER ){

    $respuesta = new stdClass();
    $respuesta->mensaje = 'OH NO! eleccion';
    $respuesta->tipo =  \core\output\notification::NOTIFY_ERROR;

    $objetoselegidos = json_decode($objetosjson);

    global $DB;

    $usuariogamificado = $DB->get_record('gmdl_usuario',array('id' => $gmuserid));

    $objetoselegidosfinal = array();

    if( $usuariogamificado->mdl_id_usuario == $USER->id ){

        if( $objetoselegidos != 0 ){
            $objetos = $DB->get_records('gmdl_objeto_desbloqueado', array('gmdl_usuario_id' => $gmuserid, 'elegido' => 1));

            foreach ($objetos as $objeto){
                $objeto->elegido=0;
                $DB->update_record('gmdl_objeto_desbloqueado',$objeto);
            }
        }

        foreach ($objetoselegidos as $objetoelegido) {

            $bandera = 0;

            if( $objetoelegido != 0 ){

                $bandera = -1;

                try {
                    $objeto = $DB->get_record('gmdl_objeto_desbloqueado', array('gmdl_objeto_id' => $objetoelegido, 'gmdl_usuario_id' => $gmuserid), '*', MUST_EXIST);
                    $desbloqueado = true;
                } catch (dml_exception $e) {
                    $desbloqueado = false;
                }

                $sql = "SELECT COUNT(*) FROM( ";
                $sql .= " SELECT {gmdl_objeto_desbloqueado}.id as id,";
                $sql .= " {gmdl_objeto_desbloqueado}.elegido as elegido,";
                $sql .= " {gmdl_objeto_desbloqueado}.gmdl_usuario_id as userid";
                $sql .= " FROM (";
                $sql .= " SELECT {gmdl_objeto}.id as id";
                $sql .= " FROM (SELECT {gmdl_objeto}.gmdl_tipo_objeto_id as id FROM {gmdl_objeto} WHERE {gmdl_objeto}.id = $objetoelegido) as tipoobjetoid";
                $sql .= " INNER JOIN {gmdl_objeto} ON tipoobjetoid.id = {gmdl_objeto}.gmdl_tipo_objeto_id) as objetosdemismotipo";
                $sql .= " INNER JOIN {gmdl_objeto_desbloqueado}";
                $sql .= " ON objetosdemismotipo.id = {gmdl_objeto_desbloqueado}.id";
                $sql .= " ) as objetosfinal WHERE objetosfinal.elegido = 1 AND objetosfinal.userid = $gmuserid";

                if( $desbloqueado ) {
                    $bandera = -2;
                    if ($DB->count_records_sql($sql) == 0) {

                        $objeto->elegido = 1;
                        $DB->update_record('gmdl_objeto_desbloqueado', $objeto);
                        $bandera = 1;

                    }
                }

            }

            $objetoselegidosfinal[] = (object)[
                'idobjeto' => $objetoelegido,
                'bandera' => $bandera,
                'nombre' => ( $bandera==1||$bandera==-1? $DB->get_record('gmdl_objeto',array('id' => $objetoelegido))->nombre : 'Default' )
            ];

        }

        return obtener_mensaje_eleccion($objetoselegidosfinal);

    }else{

        $respuesta->mensaje = "El valor de gmuserid no corresponde a tu usuario actual, te caché";
        $respuesta->tipo =  \core\output\notification::NOTIFY_ERROR;

    }

    return $respuesta;

}

function obtener_mensaje_eleccion( $objetoselegidos ){

    $respuesta = new stdClass();
    $respuesta->mensaje = 'OH NO! compra';
    $respuesta->tipo =  \core\output\notification::NOTIFY_ERROR;

    foreach ( $objetoselegidos as $objetoelegido ){

        if($objetoelegido->bandera == 1 || $objetoelegido->bandera == 0){

            $respuesta->mensaje = "Cambios guardados";
            $respuesta->tipo = \core\output\notification::NOTIFY_SUCCESS;

        }elseif ( $objetoelegido->bandera == -1 ){

            $respuesta->mensaje = "Cambios guardados, advertencia: seleccionaste un objeto que no ten&iacute;as comprado";
            $respuesta->tipo = \core\output\notification::NOTIFY_WARNING;

        }else{

            $respuesta->mensaje = "Que esta pasando";
            $respuesta->tipo = \core\output\notification::NOTIFY_ERROR;

        }

    }

    return $respuesta;

}


function obtener_usuario_gamedle($moodleUserId)
    {
        global $DB;
        return $DB->get_record('gmdl_usuario', $conditions=array("mdl_id_usuario" => $moodleUserId), $fields='*', $strictness=IGNORE_MISSING)->id;
    }

function insertar_usuario($usuario)
    {
        global $DB;
        $data = new stdClass();
        $data->mdl_id_usuario = $usuario;
        $data->nivel_actual = 1;
        $data->experiencia_actual = 0;
        return $DB->insert_record('gmdl_usuario', $data, $returnid=true, $bulk=false);
    }