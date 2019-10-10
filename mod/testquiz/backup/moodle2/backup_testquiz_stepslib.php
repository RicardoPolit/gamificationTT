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
 * Define the complete testquiz structure for backup, with file and id annotations
 *
 * @package mod_testquiz
 * @subpackage backup-moodle2
 * @copyright 2018 Stephen Bourget
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete testquiz structure for backup, with file and id annotations
 *
 * @package mod_testquiz
 * @subpackage backup-moodle2
 * @copyright 2018 Stephen Bourget
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_testquiz_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines structure for data backup.
     * @return object
     */
    protected function define_structure() {

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $testquiz = new backup_nested_element('testquiz', array('id'), array(
            'course', 'name', 'intro', 'introformat', 'timecreated',
            'timemodified', 'questioncategory', 'grade', 'completionscore'));

        $scores = new backup_nested_element('scores');

        $score = new backup_nested_element('score', array('id'), array(
            'testquizid', 'userid', 'score', 'timecreated'));
        // Build the tree.

        $testquiz->add_child($scores);
        $scores->add_child($score);

        // Define sources.

        $testquiz->set_source_table('testquiz', array('id' => backup::VAR_ACTIVITYID));

        // All the rest of elements only happen if we are including user info.
        if ($userinfo) {

            $score->set_source_sql('
            SELECT *
              FROM {testquiz_scores}
             WHERE testquizid = ?',
            array(backup::VAR_PARENTID));

        }

        // Define id annotations.
        $score->annotate_ids('user', 'userid');

        // Define file annotations.
        $testquiz->annotate_files('mod_testquiz', 'intro', null); // This file area hasn't itemid.

        // Return the root element (testquiz), wrapped into standard activity structure.
        return $this->prepare_activity_structure($testquiz);

    }
}
