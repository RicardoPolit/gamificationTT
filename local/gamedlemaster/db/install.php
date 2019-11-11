<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 3/04/19
 * Time: 06:47 PM
 */

require_once('gmdl_catalogue.php');
function xmldb_local_gamedlemaster_install()
{
    copiarUsuariosInstall();
    definirCatalogos();
}

function copiarUsuariosInstall()
{
    global $DB;
    $result = $DB->get_records("user", array()); // get all records in mdl_user table

    foreach ($result as $record) {
        $data = new stdClass();
        $data->mdl_id_usuario = $record->id;
        $data->nivel_actual = 1;
        $data->experiencia_actual = 0;
        $data->experiencia_nivel = 0;

        $DB->insert_record("gmdl_usuario", $data);
    }
}


function definirCatalogos()
{
    guardarCatalogosDificultadCPU2019101501();
    guardarCatalogosRarezaObjetos();
    guardarCatalogosTipoObjetos();
    guardarCatalogosObjetosTiposBordes();
    guardarCatalogosObjetosImagenes();
    guardarCatalogosObjetosColorBordes();
}
