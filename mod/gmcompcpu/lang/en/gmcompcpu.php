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
 * English strings for gmcompcpu
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_gmcompcpu
 * @copyright  2014 John Okely <john@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['achievedhighscoreof'] = 'Achieved a high score of {$a}';
$string['attempt'] = 'Attempt #{$a}';
$string['completioncpudiff'] = 'El estudiante debe de vencer al menos a la dificultad:';
$string['completioncpudiffgroup'] = 'Dificultad requerida';
$string['completioncpudiffgroup_help'] = 'Si se activa, requiere que una minima dificultad del cpu sea vencida antes de que la actividad sea completada';
$string['completionscore'] = 'Student must achieve a minimum score of:';
$string['completionscoregroup'] = 'Require score';
$string['completionscoregroup_help'] = 'If enabled, you can require a minimum score is met before the activity is marked as complete.

Each question is worth 1000 points when answered correctly on the first try, so you may want to set the default to:

(Number of questions x 1000)';
$string['endofgame'] = 'Your score was: {$a}. Press space or click to restart.';
$string['emptyquiz'] = 'There are no multiple choice questions in the selected category.';
$string['eventgamestarted'] = 'gmcompcpu game started';
$string['eventgamescoreadded'] = 'gmcompcpu score recorded';
$string['eventgamescoresviewed'] = 'gmcompcpu scores viewed';
$string['fullscreen'] = 'Fullscreen';
$string['howtoplay'] = 'How to Play';
$string['howtoplay_help'] = 'You can move the ship by using the arrow keys, or by dragging it with the mouse.

Press the spacebar or click the mouse button to shoot, or tap with two fingers anywhere on the game.

Clear as many questions as possible by shooting the correct answer.  Good Luck!';
$string['modulename_help'] = 'Students procastinating too much? Are they playing games instead of studying? Well now you can motivate them by allowing them to do both at once!

gmcompcpu is an activity module that loads quiz questions from the course it\'s added to. The possible answers come down as space ships and you have to shoot the correct one.

**Note**: gmcompcpu is designed to promote learning rather than for assessment. Students will have infinite attempts with instant feedback. For this reason, only add questions you want students to learn the answer to, rather than questions you want to assess if they have learned';
$string['modulenameplural'] = 'gmcompcpu games';
$string['modulename'] = 'gmcompcpu';
$string['notyetplayed'] = 'Not yet played';
$string['achievedhighscoreof'] = 'Achieved a high score of {$a}';
$string['playedxtimeswithhighscore'] = 'Played {$a->times} times. The last game ended with a high score of {$a->score}';
$string['pluginadministration'] = 'gmcompcpu administration';
$string['pluginname'] = 'gmcompcpu';
$string['playerscores'] = 'Player scores';
$string['privacy:metadata:gmcompcpu_scores'] = 'Information about the user\'s chosen answer(s) for a given choice activity';
$string['privacy:metadata:gmcompcpu_scores:gmcompcpuid'] = 'The ID of the gmcompcpu activity the user is providing answer for';
$string['privacy:metadata:gmcompcpu_scores:score'] = 'The score of the user during that playthrough.';
$string['privacy:metadata:gmcompcpu_scores:timecreated'] = 'The timestamp indicating when the gmcompcpu was played by the user';
$string['privacy:metadata:gmcompcpu_scores:userid'] = 'The ID of the user playing this gmcompcpu activity';
$string['questioncategory'] = 'Question category';
$string['questioncategory_help'] = 'Select the category from the question bank to use in the game.

Note that you should only select questions that are not critical to assessment later on. The quiz game is similar to creating a quiz with infinite attempts and instant feedback on whether you got something right or wrong.

**Note**: gmcompcpu is designed to promote learning rather than for assessment. Students will have infinite attempts with instant feedback. For this reason, only add questions you want students to learn the answer to, rather than questions you want to assess if they have learned';
$string['gmcompcpufieldset'] = 'Custom example fieldset';
$string['gmcompcpuname_help'] = 'What is the name of this gmcompcpu?';
$string['gmcompcpuname'] = 'gmcompcpu name';
$string['gmcompcpu'] = 'gmcompcpu';
$string['gmcompcpu:addinstance'] = 'Add a gmcompcpu instance';
$string['gmcompcpu:view'] = 'View gmcompcpu';
$string['gmcompcpu:viewallscores'] = 'View player scores';
$string['removescores'] = 'Remove all user scores';
$string['score'] = 'Score: {$a->score} Lives: {$a->lives}';
$string['scoreheader'] = 'Score';
$string['scoreslink'] = 'View all attempts';
$string['scoreslinkhelp'] = 'View all player attempts and scores';
$string['spacetostart'] = 'Press space or click to start';
$string['sound'] = 'Sound';
