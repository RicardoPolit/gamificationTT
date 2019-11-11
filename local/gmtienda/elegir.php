<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

require_once('../../config.php');

$gmuserid = optional_param('gmuserid', 0, PARAM_INT);
$objetosjson = optional_param('objetosjson',0,PARAM_RAW);

$course     = $DB->get_record('course', array('id' => 1), '*', MUST_EXIST);
$title = get_string('perfilgamificado', 'local_gmtienda');
$pagetitle = $title;
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/gmtienda/perfilgamificado.php');
require_login($course,true);
$PAGE->set_title($title);
$PAGE->set_heading('Procesando seleccion');
$renderer = $PAGE->get_renderer('local_gmtienda');

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

    echo $OUTPUT->header();

    echo $renderer->render_choices_answer($objetoselegidosfinal);

    echo $OUTPUT->footer();

}else{

    echo "El valor de gmuserid no corresponde a tu usuario actual, te caché";

}

