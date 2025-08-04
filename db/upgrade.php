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
 * Upgrade script for availability_ipaddress.
 *
 * @package    availability_ipaddress
 * @copyright  04/08/2025 LdesignMedia.nl - Luuk Verhoeven
 * @author     Vincent Cornelis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade function.
 *
 * @param int $oldversion The old version of the plugin
 *
 * @return bool
 */
function xmldb_availability_ipaddress_upgrade(int $oldversion): bool {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2025070400) {
        // Define table availability_ipaddress_pre to be created.
        $table = new xmldb_table('availability_ipaddress_pre');

        // Adding fields to table availability_ipaddress_pre.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('ipaddresses', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('enabled', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table availability_ipaddress_pre.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table availability_ipaddress_pre.
        $table->add_index('enabled_sortorder', XMLDB_INDEX_NOTUNIQUE, ['enabled', 'sortorder']);

        // Conditionally launch create table for availability_ipaddress_pre.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Availability_ipaddress savepoint reached.
        upgrade_plugin_savepoint(true, 2025070400, 'availability', 'ipaddress');
    }

    if ($oldversion < 2025080401) {
        // Remove sortorder field from availability_ipaddress_pre table.
        $table = new xmldb_table('availability_ipaddress_pre');

        // Drop the index that includes sortorder.
        $index = new xmldb_index('enabled_sortorder', XMLDB_INDEX_NOTUNIQUE, ['enabled', 'sortorder']);
        if ($dbman->index_exists($table, $index)) {
            $dbman->drop_index($table, $index);
        }

        // Drop the sortorder field.
        $field = new xmldb_field('sortorder');
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Add a new index for enabled only.
        $index = new xmldb_index('enabled', XMLDB_INDEX_NOTUNIQUE, ['enabled']);
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Availability_ipaddress savepoint reached.
        upgrade_plugin_savepoint(true, 2025080401, 'availability', 'ipaddress');
    }

    return true;
}
