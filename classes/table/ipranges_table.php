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
 * Table class for displaying IP address ranges.
 *
 * @package    availability_ipaddress
 * @copyright  04/08/2025 LdesignMedia.nl - Luuk Verhoeven
 * @author     Vincent Cornelis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_ipaddress\table;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/tablelib.php');

use table_sql;
use html_writer;
use moodle_url;
use pix_icon;
use confirm_action;

/**
 * Table class for IP address ranges.
 *
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package    availability_ipaddress
 * @copyright  04/08/2025 LdesignMedia.nl - Luuk Verhoeven
 * @author     Vincent Cornelis
 */
class ipranges_table extends table_sql {

    /**
     * @var moodle_url The base URL for the page.
     */
    public $baseurl;

    /**
     * Constructor.
     *
     * @param string $uniqueid    Unique ID for the table.
     * @param moodle_url $baseurl The base URL for the page.
     */
    public function __construct(string $uniqueid, moodle_url $baseurl) {
        parent::__construct($uniqueid);
        $this->baseurl = $baseurl;

        // Define columns and headers.
        $columns = ['name', 'description', 'ipaddresses', 'enabled', 'actions'];
        $headers = [
            get_string('name'),
            get_string('description'),
            get_string('ipaddresses', 'availability_ipaddress'),
            get_string('enabled', 'availability_ipaddress'),
            get_string('actions'),
        ];

        $this->define_columns($columns);
        $this->define_headers($headers);

        // Set attributes.
        $this->set_attribute('class', 'generaltable');
        $this->sortable(true, 'name', SORT_ASC);
        $this->no_sorting('description');
        $this->no_sorting('actions');
        $this->collapsible(false);
        $this->pageable(true);
        $this->is_downloadable(false);

        $this->define_baseurl($baseurl);
    }

    /**
     * Set up the SQL query.
     *
     * @param int $pagesize        Number of records per page.
     * @param bool $useinitialsbar Whether to use the initials bar.
     *
     * @return void
     */
    public function set_sql_data(int $pagesize = 30, bool $useinitialsbar = false): void {
        $fields = 'id, name, description, ipaddresses, enabled, timecreated, timemodified';
        $from = '{availability_ipaddress_pre}';
        $where = '1=1';
        $params = [];

        $this->set_sql($fields, $from, $where, $params);
        $this->set_count_sql("SELECT COUNT(*) FROM {availability_ipaddress_pre}");
    }

    /**
     * Column for name.
     *
     * @param \stdClass $range The range record.
     *
     * @return string
     */
    public function col_name(\stdClass $range): string {
        return format_string($range->name);
    }

    /**
     * Column for description.
     *
     * @param \stdClass $range The range record.
     *
     * @return string
     */
    public function col_description(\stdClass $range): string {
        if (empty($range->description)) {
            return '-';
        }
        $description = format_string($range->description);
        // Truncate if too long.
        if (strlen($description) > 100) {
            $truncated = substr($description, 0, 97) . '...';

            return html_writer::tag('span', $truncated, ['title' => $description]);
        }

        return $description;
    }

    /**
     * Column for IP addresses.
     *
     * @param \stdClass $range The range record.
     *
     * @return string
     */
    public function col_ipaddresses(\stdClass $range): string {
        $ips = s($range->ipaddresses);
        // Truncate if too long and add tooltip.
        if (strlen($ips) > 50) {
            $truncated = substr($ips, 0, 47) . '...';

            return html_writer::tag(
                'span',
                html_writer::tag('code', $truncated),
                ['title' => $ips]
            );
        }

        return html_writer::tag('code', $ips);
    }

    /**
     * Column for enabled status.
     *
     * @param \stdClass $range The range record.
     *
     * @return string
     */
    public function col_enabled(\stdClass $range): string {
        return $range->enabled ? get_string('yes') : get_string('no');
    }

    /**
     * Column for actions.
     *
     * @param \stdClass $range The range record.
     *
     * @return string
     */
    public function col_actions(\stdClass $range): string {
        global $OUTPUT;

        $actions = [];

        // Edit action.
        $editurl = new moodle_url($this->baseurl, ['action' => 'edit', 'id' => $range->id]);
        $actions[] = $OUTPUT->action_icon($editurl,
            new pix_icon('t/edit', get_string('edit')));

        // Toggle action.
        $toggleurl = new moodle_url($this->baseurl, ['action' => 'toggle', 'id' => $range->id, 'sesskey' => sesskey()]);
        $toggleicon = $range->enabled ? 't/hide' : 't/show';
        $togglestring = $range->enabled ? get_string('disable') : get_string('enable');

        // Check if range is in use and add confirmation if disabling.
        if ($range->enabled) {
            $usage = \availability_ipaddress\helper::is_range_in_use($range->id);
            if ($usage['inuse']) {
                // Create confirmation message with usage details.
                $message = \availability_ipaddress\helper::get_range_usage_html($range->id);
                $message .= \html_writer::tag('p', get_string('confirm_disable_range', 'availability_ipaddress'),
                    ['class' => 'font-weight-bold']);
                $actions[] = $OUTPUT->action_icon($toggleurl, new pix_icon($toggleicon, $togglestring),
                    new confirm_action($message));
            } else {
                $actions[] = $OUTPUT->action_icon($toggleurl, new pix_icon($toggleicon, $togglestring));
            }
        } else {
            // Enabling doesn't need confirmation.
            $actions[] = $OUTPUT->action_icon($toggleurl, new pix_icon($toggleicon, $togglestring));
        }

        // Delete action.
        $deleteurl = new moodle_url($this->baseurl, [
            'action' => 'delete',
            'id' => $range->id,
            'sesskey' => sesskey(),
        ]);

        // Check if range is in use and add usage info to confirmation.
        $usage = \availability_ipaddress\helper::is_range_in_use($range->id);
        if ($usage['inuse']) {
            $message = \availability_ipaddress\helper::get_range_usage_html($range->id);
            $message .= \html_writer::tag('p', get_string('confirm_delete_range', 'availability_ipaddress'),
                ['class' => 'font-weight-bold']);
        } else {
            $message = get_string('confirm_delete_range', 'availability_ipaddress');
        }

        $actions[] = $OUTPUT->action_icon($deleteurl,
            new pix_icon('t/delete', get_string('delete')),
            new confirm_action($message));

        return implode(' ', $actions);
    }

}
