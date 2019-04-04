<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 3/04/19
 * Time: 06:47 PM
 */

function xmldb_local_gamedlemaster_install()
{
    global $DB;

    copiarUsuarios();
}

function copiarUsuarios()
{
    $result = $DB->get_records("user", array()); // get all records in mdl_user table
    $i = 0;
    foreach ($result as $record) {
        $data = new stdClass();
        $data->mdl_id_usuario = $record->id;
        $data->nivell_actual = 1;
        $data->experiencia_actual = 0;
        //echo $record->username;
        if ($i == 0) {
            echo 'Así se ve un objeto de tipo usuario:\n';
            echo var_dump($record);

        }
        $DB->insert_record("gmdl_usuario", $data);
        $i = $i + 1;
    }
}