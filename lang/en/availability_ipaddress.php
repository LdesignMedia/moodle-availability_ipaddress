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
 * EN language file.
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_ipaddress
 * @copyright 2019-05-14 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

// We like comments and our own sorting.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

$string['pluginname'] = 'IP address';
$string['description'] = 'Restrict access by ip-address or subnet';
$string['title'] = 'IP address';
$string['require_condition'] = 'ip-address/subnet is allowed (Your IP:{$a})';
$string['require_condition_not'] = 'ip-address/subnet is not blocked (Your IP:{$a})';

// Errors.
$string['error_ipaddress'] = 'Incorrect ip-address/subnet format';

// Javascript strings.
$string['js:ipaddress'] = 'Require network address';

// Privacy provider.
$string['privacy:metadata'] = 'The restriction by activity ipaddress plugin does not store any personal data.';

// Predefined ranges.
$string['setting:manage_predefined_ranges'] = 'IP address - Manage predefined IP ranges';
$string['manage_predefined_ranges'] = 'Manage predefined IP ranges';
$string['predefined_ranges'] = 'Predefined IP ranges';
$string['custom_ipaddress'] = 'Custom IP address(es)';
$string['use_predefined'] = 'Use predefined IP addresses';
$string['range_name'] = 'Range name';
$string['range_name_help'] = 'A descriptive name for this IP range, e.g., "Campus Network" or "Library Computers"';
$string['ipaddresses'] = 'IP addresses';
$string['ipaddresses_help'] = 'Enter IP addresses separated by commas. Supports single IPs (192.168.1.1), ranges (192.168.1.1-255), and subnets (192.168.1.0/24)';
$string['ipaddresses_help_help'] = '<p>Enter one or more IP addresses or ranges, separated by commas.</p>
<p><strong>Examples:</strong></p>
<ul>
    <li><strong>Single IP:</strong> <code>192.168.1.1</code></li>
    <li><strong>IP range:</strong> <code>192.168.1.1-255</code></li>
    <li><strong>Subnet:</strong> <code>192.168.1.0/24</code></li>
    <li><strong>Multiple:</strong> <code>192.168.1.1,10.0.0.0/8,172.16.0.1-255</code></li>
</ul>';
$string['enabled'] = 'Enabled';
$string['sortorder'] = 'Sort order';
$string['existing_ranges'] = 'Existing IP ranges';
$string['range_created'] = 'IP range created successfully';
$string['range_updated'] = 'IP range updated successfully';
$string['range_deleted'] = 'IP range deleted successfully';
$string['confirm_delete_range'] = 'Deleting this IP range will remove it from all restrictions where it is used. Are you sure you want to permanently delete it?';
$string['range_in_use_count'] = 'This IP range is currently used in {$a} restriction(s).';
$string['and_x_more'] = '... and {$a} more.';
$string['confirm_disable_range'] = 'This IP range is currently in use. Disabling it will remove it from all restrictions where it is used. Are you sure you want to continue?';
$string['range_in_use_title'] = 'IP Range In Use';
$string['range_disabled_and_removed'] = 'IP range disabled and removed from {$a} restriction(s).';
$string['range_deleted_and_removed'] = 'IP range deleted and removed from {$a} restriction(s).';
