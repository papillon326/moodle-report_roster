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
 * Local library functions for the roster report.
 *
 * @package   report_roster
 * @copyright 2013 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

define('ROSTER_MODE_DISPLAY', 'display');
define('ROSTER_MODE_PRINT', 'print');

define('ROSTER_ORDER_FULLNAME', 'fullname');
define('ROSTER_ORDER_IDNUMBER', 'idnumber');

function report_roster_get_group_options($id) {
    $groupsfromdb = groups_get_all_groups($id);
    $groups = array();
    foreach ($groupsfromdb as $key => $value) {
        $groups[$key] = $value->name;
    }
    return $groups;
}

function report_roster_output_action_buttons($id, $group, $mode, $order, $url) {
    global $OUTPUT;

    $displayoptions = array(
        ROSTER_MODE_DISPLAY => get_string('webmode', 'report_roster'),
        ROSTER_MODE_PRINT => get_string('printmode', 'report_roster'),
        );
    $groups = report_roster_get_group_options($id);
    $orderoptions = array(
        ROSTER_ORDER_FULLNAME => get_string('orderfullname', 'report_roster'),
        ROSTER_ORDER_IDNUMBER => get_string('orderidnumber', 'report_roster'),
        );
    
    $groupurl = clone $url;
    $groupurl->params(array('mode' => $mode, 'order' => $order));
    $modeurl = clone $url;
    $modeurl->params(array('group' => $group, 'order' => $order));
    $orderurl = clone $url;
    $orderurl->params(array('mode' => $mode, 'group' => $group));
    
    $url->params(array('mode' => $mode, 'group' => $group, 'order' => $order));
    
    $html = html_writer::start_tag('div');
    
    $select = new single_select($groupurl, 'group', $groups, $group, array('' => get_string('allusers', 'report_roster')));
    $select->label = get_string('group');
    $html .= $OUTPUT->render($select);
    
    $select = new single_select($modeurl, 'mode', $displayoptions, $mode);
    $select->label = get_string('displaymode', 'report_roster');
    $html .= $OUTPUT->render($select);
    
    $select = new single_select($orderurl, 'order', $orderoptions, $order);
    $select->label = get_string('displayorder', 'report_roster');
    $html .= $OUTPUT->render($select);
    
    $html .= html_writer::end_tag('div');
    
    return $html;
}
