<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 3/04/19
 * Time: 06:47 PM
 */

require_once('gmdl_catalogue.php');


    function xmldb_local_gamedlemaster_install() {

        global $DB;

        try {
            $transaction = $DB->start_delegated_transaction();

            gamify_existing_users();
            local_gamedlemaster_log::success(
                "Gamedle users avatars created", 'CREATE');

            definirCatalogos();
            local_gamedlemaster_log::success(
                "Gamedle system catalogs created", 'CREATE');

            $transaction->allow_commit();

        } catch(Exception $ex) {
            $transaction->rollback($ex);
            local_gamedlemaster_log::error(
                "Gamedle users avatars nor created",'CREATE');
        }
    }

    function gamify_existing_users() {

        $TABLE_USER = 'user';

        global $DB;
        $users = $DB->get_records("user", null, '', 'id');

        foreach ($users as $user) {
            local_gamedlemaster_dao::create_gamified_user($user->id);
        }
    }

    function definirCatalogos() {
        guardarCatalogosDificultadCPU2019101501();
        guardarCatalogosRarezaObjetos();
        guardarCatalogosTipoObjetos();
        guardarCatalogosObjetosTiposBordes();
        guardarCatalogosObjetosImagenes();
        guardarCatalogosObjetosColorBordes();
    }
