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

class block_gmcz_dao {

    const TABLE = 'block_gmcz';
    const TABLE_USER = 'user';
    const AVATAR_PARTS = array(
        'hair',
        'head',
        'rightarm',
        'leftarm',
        'torso',
        'legs'
    );

    /**
     * TODO: Write docs
     */
    static function create_avatar( $userid, $avatarname) {

        global $DB;

        $avatar = new stdClass();
        /*$avatar->mdl_user = $userid;
        $avatar->hair = $avatarname;
        $avatar->head = $avatarname;
        $avatar->rightarm = $avatarname;
        $avatar->leftarm = $avatarname;
        $avatar->torso = $avatarname;
        $avatar->legs = $avatarname;*/

        foreach (self::AVATAR_PARTS as $part) {
            $avatar->$part = $avatarname;
        }

        local_gamedlemaster_log::info(
            "Avatar \"$avatarname\" created for userid $userid", 'CREATE');
        return $DB->insert_record(self::TABLE, $avatar);
    }

    static function get_avatar($userid) {
        global $DB;
        global $CFG;

        $path = "{$CFG->wwwroot}/blocks/gmcz/pix";
        $avatar_obj = $DB->get_record(self::TABLE, array(
            'id' => $userid
        ));

        $avatar = array();
        foreach (self::AVATAR_PARTS as $part) {
            $avatar[] = array(
                'name' => $part,
                'src' => "$path/{$avatar_obj->$part}/$part.png"
            );
        }

        local_gamedlemaster_log::info($avatar);
        return $avatar;
    }
}
?>
