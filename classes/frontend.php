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
 * Front-end class
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_ipaddress
 * @copyright 2019-05-14 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

namespace availability_ipaddress;

/**
 * Class frontend
 *
 * @package   availability_ipaddress
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2019-05-14 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */
class frontend extends \core_availability\frontend {

    /**
     * get_javascript_strings
     *
     * @return array
     */
    protected function get_javascript_strings(): array {
        return [
            'js:ipaddress',
            'error_ipaddress',
            'predefined_ranges',
            'custom_ipaddress',
            'use_predefined',
        ];
    }

    /**
     * Get additional parameters for the JavaScript module.
     *
     * Note: Cannot add type declaration for $course parameter as the parent
     * core_availability\frontend::get_javascript_init_params() method doesn't
     * have it, and PHP requires compatibility with parent method signatures.
     *
     * @param \stdClass $course           Course object
     * @param \cm_info|null $cm           Course module
     * @param \section_info|null $section Section
     *
     * @return array
     */
    protected function get_javascript_init_params($course, ?\cm_info $cm = null, ?\section_info $section = null): array {
        global $DB;

        // Get enabled predefined IP ranges.
        $ranges = $DB->get_records('availability_ipaddress_pre', ['enabled' => 1], 'name', 'id, name, ipaddresses');

        // Format for JavaScript.
        $rangedata = [];
        foreach ($ranges as $range) {
            $rangedata[] = [
                'id' => $range->id,
                'name' => format_string($range->name),
                'ipaddresses' => $range->ipaddresses,
            ];
        }

        return [$rangedata];
    }

}
