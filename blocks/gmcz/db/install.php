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
        $TABLE_AVATAR = 'avatar';
        $DEFAULT_AVATAR = 'default';

        global $DB;
        $users = $DB->get_records($TABLE_USER);
        local_gamedlemaster_log($users);

        foreach($users as $user) {
            $avatar = new stdClass();
            $avatar->mdl_user = $user->id;
            $avatar->hair = $DEFAULT_AVATAR;
            $avatar->head = $DEFAULT_AVATAR;
            $avatar->rightarm = $DEFAULT_AVATAR;
            $avatar->leftarm = $DEFAULT_AVATAR;
            $avatar->torso = $DEFAULT_AVATAR;
            $avatar->legs = $DEFAULT_AVATAR;

            local_gamedlemaster_log::info(
                $DB->insert_record($TABLE_AVATAR, $avatar)
            );
        }
    }

    function xmldb_block_gmcz_install () {

        global $DB;

        try {
            $transaction = $DB->start_delegated_transaction();
            set_default_avatars();

            $transaction->allow_commit();

            local_gamedlemaster_log::success(
                "Gamedle users avatars created",'DELETE');

        } catch(Exception $ex) {
            $transaction->rollback($ex);
            local_gamedlemaster_log::error(
                "Gamedle users avatars nor created",'CREATE');
        }
    }
