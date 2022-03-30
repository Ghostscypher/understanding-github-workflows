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
 * Index file for local_staffmanager
 *
 * @package    local_staffmanager
 * @copyright  2020 Brian Ngetich
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once '../../config.php';

// require_login(); // Must be logged in

// Global variables
global $USER, $DB, $CFG;

$PAGE->set_url('/local/staff_manager/index.php');
$PAGE->set_context(context_system::instance());
$PAGE->requires->js('/local/staffmanager/assets/js/staffmanager.js');

$PAGE->set_title(get_string('staffmanager', 'local_staffmanager'));
$PAGE->set_heading(get_string('staffmanager', 'local_staffmanager'));

// Data
$search_data = new stdClass();

$search_data->month = (int) optional_param('month', '', PARAM_TEXT);
$search_data->year = (int) optional_param('year', '', PARAM_TEXT);

// Database
$start = mktime(0, 0, 0, $search_data->month, 1, $search_data->year);
$end = mktime(23, 59, 59, $search_data->month + 1, 0, $search_data->year);

$sql = sprintf(
    "SELECT DISTINCT(grades_table.usermodified) as grader_id
    FROM mdl_grade_grades AS grades_table 
    LEFT JOIN mdl_user AS grader ON grader.id = grades_table.usermodified
    WHERE grades_table.usermodified <> '' 
    AND grades_table.finalgrade > 0 
    AND grades_table.timemodified >= '%s'
    AND grades_table.timemodified <= '%s'",
    $start, $end
);

$graders = $DB->get_records_sql($sql);

foreach($graders as $key => $grader){
    $graders[$key] = $DB->get_record('user', ['id' => $grader->graderid], 'firstname', 'lastname', 'email'); 
}

$results = new stdClass();
$results->data = array_values($graders); // NOTE: Very important to remember convert the values 
                                        // of query results to array

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_staffmanager/searchbar', []);

echo $OUTPUT->render_from_template('local_staffmanager/searchresults', $results);

echo $OUTPUT->footer();
