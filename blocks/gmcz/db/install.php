<?php
/*
 * UNIVERSITY:  INSTITUTO POLITECNICO NACIONAL (IPN)
 *              ESCUELA SUPERIOR DE COMPUTO (ESCOM)
 *
 *   PROJECT:     Gamedle
 *   PROFESSOR:   Sandra Bautista, Andres Catalan.
 *
 * DESARROLLADORES: Daniel Ortega (GitLab/Github @DanielOrtegaZ)
*/

    function set_default_avatars () {

        $TABLE_USER = 'user';
        $DEFAULT_AVATAR = 'default';

        global $DB;
        $users = $DB->get_records($TABLE_USER, null, '', 'id');

        foreach($users as $user) {
            block_gmcz_dao::create_avatar( $user->id, $DEFAULT_AVATAR);
        }
    }

    function xmldb_block_gmcz_install () {

        global $DB;

        try {
            $transaction = $DB->start_delegated_transaction();

            set_default_avatars();

            $transaction->allow_commit();

            local_gamedlemaster_log::success(
                "Gamedle users avatars created", 'CREATE');

        } catch(Exception $ex) {
            $transaction->rollback($ex);
            local_gamedlemaster_log::error(
                "Gamedle users avatars nor created",'CREATE');
        }
    }
