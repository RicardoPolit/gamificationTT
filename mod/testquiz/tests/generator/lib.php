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
 * mod_testquiz data generator.
 *
 * @package    mod_testquiz
 * @category   test
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * mod_testquiz data generator class.
 *
 * @package    mod_testquiz
 * @category   test
 * @copyright  2018 Stephen Bourget
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_testquiz_generator extends testing_module_generator {

    /**
     * Create an instance of mod_testquiz with some default settings
     * @param object $record
     * @param array $options
     */
    public function create_instance($record = null, array $options = null) {

        // Add default values for testquiz.
        $record = (array)$record + array(
            'questioncategory' => 0,
            'grade' => 100,
            'completionscore' => 0,
        );

        return parent::create_instance($record, (array)$options);
    }

    /**
     * Create a testquiz playthrough
     * @param object $testquiz testquiz object
     * @param array $record testquiz settings
     * @return object
     */
    public function create_content($testquiz, $record = array()) {
        global $DB, $USER;
        $now = time();
        $record = (array)$record + array(
            'testquizid' => $testquiz->id,
            'timecreated' => $now,
            'userid' => $USER->id,
            'score' => mt_rand (0, 50000),
        );

        $id = $DB->insert_record('testquiz_scores', $record);

        return $DB->get_record('testquiz_scores', array('id' => $id), '*', MUST_EXIST);
    }
}
