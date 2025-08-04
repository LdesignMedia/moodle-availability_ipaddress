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
 * Manage predefined IP address ranges.
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package    availability_ipaddress
 * @copyright  04/08/2025 LdesignMedia.nl - Luuk Verhoeven
 * @author     Vincent Cornelis
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('availability_ipaddress_ranges');

$action = optional_param('action', '', PARAM_ALPHA);
$id = optional_param('id', 0, PARAM_INT);

$PAGE->set_url('/availability/condition/ipaddress/manage_ranges.php');
$PAGE->set_title(get_string('manage_predefined_ranges', 'availability_ipaddress'));
$PAGE->set_heading(get_string('manage_predefined_ranges', 'availability_ipaddress'));

// Handle actions.
if ($action === 'delete' && confirm_sesskey()) {
    // Remove from all restrictions before deleting.
    $removed = \availability_ipaddress\helper::remove_range_from_restrictions($id);

    $DB->delete_records('availability_ipaddress_pre', ['id' => $id]);

    if ($removed > 0) {
        redirect($PAGE->url, get_string('range_deleted_and_removed', 'availability_ipaddress', $removed),
            null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        redirect($PAGE->url, get_string('range_deleted', 'availability_ipaddress'), null,
            \core\output\notification::NOTIFY_SUCCESS);
    }
}

if ($action === 'toggle' && confirm_sesskey()) {
    $record = $DB->get_record('availability_ipaddress_pre', ['id' => $id], '*', MUST_EXIST);

    $record->enabled = !$record->enabled;
    $record->timemodified = time();
    $DB->update_record('availability_ipaddress_pre', $record);

    // If we just disabled the range, remove it from all restrictions.
    if (!$record->enabled) {
        $removed = \availability_ipaddress\helper::remove_range_from_restrictions($id);
        if ($removed > 0) {
            redirect($PAGE->url, get_string('range_disabled_and_removed', 'availability_ipaddress', $removed),
                null, \core\output\notification::NOTIFY_SUCCESS);
        }
    }

    redirect($PAGE->url);
}

// Handle form submission for adding/editing.
if ($action === 'add' || $action === 'edit') {
    $formurl = new moodle_url($PAGE->url, ['action' => $action, 'id' => $id]);
    $form = new \availability_ipaddress\form\range_form($formurl, ['id' => $id]);

    if ($form->is_cancelled()) {
        redirect($PAGE->url);
    }

    if ($data = $form->get_data()) {
        if ($data->id) {
            // Update existing.
            $data->timemodified = time();
            $DB->update_record('availability_ipaddress_pre', $data);
            redirect($PAGE->url, get_string('range_updated', 'availability_ipaddress'), null,
                \core\output\notification::NOTIFY_SUCCESS);
        } else {
            // Create new.
            $data->timecreated = time();
            $data->timemodified = time();
            $DB->insert_record('availability_ipaddress_pre', $data);
            redirect($PAGE->url, get_string('range_created', 'availability_ipaddress'), null,
                \core\output\notification::NOTIFY_SUCCESS);
        }
    }

    // Load data for editing.
    if ($action === 'edit' && $id) {
        $record = $DB->get_record('availability_ipaddress_pre', ['id' => $id], '*', MUST_EXIST);
        $form->set_data($record);
    }

    echo $OUTPUT->header();
    $form->display();
    echo $OUTPUT->footer();
    exit;
}

// Display page.
echo $OUTPUT->header();

// Add new button.
echo $OUTPUT->single_button(new moodle_url($PAGE->url, ['action' => 'add']), get_string('add'), 'get');

// Create and display table.
$table = new \availability_ipaddress\table\ipranges_table('availability-ipaddress-ranges', $PAGE->url);
$table->set_sql_data(30);
$table->out(30, true);

echo $OUTPUT->footer();
