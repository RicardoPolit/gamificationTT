<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 3/04/19
 * Time: 06:47 PM
 */

function xmldb_local_gamedlemaster_install()
{
    copiarUsuarios();
}

function copiarUsuarios()
{
    global $DB;
    $result = $DB->get_records("user", array()); // get all records in mdl_user table

    foreach ($result as $record) {
        $data = new stdClass();
        $data->mdl_id_usuario = $record->id;
        $data->nivel_actual = 1;
        $data->experiencia_actual = 0;

        $DB->insert_record("gmdl_usuario", $data);
    }
}
