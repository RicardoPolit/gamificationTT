<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Privacy Subsystem implementation for mod_gmcompcpu.
 *
 * @package    mod_gmcompcpu
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_gmcompcpu\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\deletion_criteria;
use core_privacy\local\request\helper;
use core_privacy\local\request\writer;

defined('MOODLE_INTERNAL') || die();

/**
 * Implementation of the privacy subsystem plugin provider for the gmcompcpu activity module.
 *
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
        // This plugin stores personal data.
        \core_privacy\local\metadata\provider,

        // This plugin is a core_user_data_provider.
        \core_privacy\local\request\plugin\provider {
    /**
     * Return the fields which contain personal data.
     *
     * @param collection $items a reference to the collection to use to store the metadata.
     * @return collection the updated collection of metadata items.
     */
    public static function get_metadata(collection $items) : collection {
        $items->add_database_table(
            'gmcompcpu_scores',
            [
                'gmcompcpuid' => 'privacy:metadata:gmcompcpu_scores:gmcompcpuid',
                'userid' => 'privacy:metadata:gmcompcpu_scores:userid',
                'score' => 'privacy:metadata:gmcompcpu_scores:score',
                'timecreated' => 'privacy:metadata:gmcompcpu_scores:timecreated',
            ],
            'privacy:metadata:gmcompcpu_scores'
        );

        return $items;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid the userid.
     * @return contextlist the list of contexts containing user info for the user.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        // Fetch all gmcompcpu scores.
        $sql = "SELECT c.id
                  FROM {context} c
            INNER JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
            INNER JOIN {modules} m ON m.id = cm.module AND m.name = :modname
            INNER JOIN {gmcompcpu} qg ON qg.id = cm.instance
            INNER JOIN {gmcompcpu_scores} qgs ON qgs.gmcompcpuid = qg.id
                 WHERE qgs.userid = :userid";

        $params = [
            'modname'       => 'gmcompcpu',
            'contextlevel'  => CONTEXT_MODULE,
            'userid'        => $userid,
        ];
        $contextlist = new contextlist();
        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Export personal data for the given approved_contextlist. User and context information is contained within the contextlist.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for export.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $user = $contextlist->get_user();

        list($contextsql, $contextparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);

        $sql = "SELECT cm.id AS cmid,
                       qgs.score,
                       qgs.timecreated
                  FROM {context} c
            INNER JOIN {course_modules} cm ON cm.id = c.instanceid AND c.contextlevel = :contextlevel
            INNER JOIN {modules} m ON m.id = cm.module AND m.name = :modname
            INNER JOIN {gmcompcpu} qg ON qg.id = cm.instance
            INNER JOIN {gmcompcpu_scores} qgs ON qgs.gmcompcpuid = qg.id
                 WHERE c.id {$contextsql}
                       AND qgs.userid = :userid
              ORDER BY cm.id";

        $params = ['modname' => 'gmcompcpu', 'contextlevel' => CONTEXT_MODULE, 'userid' => $user->id] + $contextparams;

        // Reference to the gmcompcpu activity seen in the last iteration of the loop. By comparing this with the current record, and
        // because we know the results are ordered, we know when we've moved to the scores for a new gmcompcpu activity and therefore
        // when we can export the complete data for the last activity.
        $lastcmid = null;

        $gmcompcpuscores = $DB->get_recordset_sql($sql, $params);
        foreach ($gmcompcpuscores as $gmcompcpuscore) {
            // If we've moved to a new gmcompcpu, then write the last gmcompcpu data and reinit the gmcompcpu data array.
            if ($lastcmid != $gmcompcpuscore->cmid) {
                if (!empty($gmcompcpudata)) {
                    $context = \context_module::instance($lastcmid);
                    self::export_gmcompcpu_data_for_user($gmcompcpudata, $context, $user);
                }
                $gmcompcpudata = [
                    'score' => [],
                    'timecreated' => [],
                ];
            }
            $gmcompcpudata['score'][] = $gmcompcpuscore->score;
            $gmcompcpudata['timecreated'][] = \core_privacy\local\request\transform::datetime($gmcompcpuscore->timecreated);
            $lastcmid = $gmcompcpuscore->cmid;
        }
        $gmcompcpuscores->close();

        // The data for the last activity won't have been written yet, so make sure to write it now!
        if (!empty($gmcompcpudata)) {
            $context = \context_module::instance($lastcmid);
            self::export_gmcompcpu_data_for_user($gmcompcpudata, $context, $user);
        }
    }

    /**
     * Export the supplied personal data for a single gmcompcpu activity, along with any generic data or area files.
     *
     * @param array $gmcompcpudata the personal data to export for the gmcompcpu.
     * @param \context_module $context the context of the gmcompcpu.
     * @param \stdClass $user the user record
     */
    protected static function export_gmcompcpu_data_for_user(array $gmcompcpudata, \context_module $context, \stdClass $user) {
        // Fetch the generic module data for the gmcompcpu.
        $contextdata = helper::get_context_data($context, $user);

        // Merge with gmcompcpu data and write it.
        $contextdata = (object)array_merge((array)$contextdata, $gmcompcpudata);
        writer::with_context($context)->export_data([], $contextdata);

        // Write generic module intro files.
        helper::export_context_files($context, $user);
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context the context to delete in.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        if (!$context instanceof \context_module) {
            return;
        }

        if ($cm = get_coursemodule_from_id('gmcompcpu', $context->instanceid)) {
            $DB->delete_records('gmcompcpu_scores', ['gmcompcpuid' => $cm->instance]);
        }
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist a list of contexts approved for deletion.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        if (empty($contextlist->count())) {
            return;
        }

        $userid = $contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $context) {

            if (!$context instanceof \context_module) {
                continue;
            }
            $instanceid = $DB->get_field('course_modules', 'instance', ['id' => $context->instanceid], MUST_EXIST);
            $DB->delete_records('gmcompcpu_scores', ['gmcompcpuid' => $instanceid, 'userid' => $userid]);
        }
    }
}