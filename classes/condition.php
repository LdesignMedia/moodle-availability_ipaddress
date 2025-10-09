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
 * Condition class
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_ipaddress
 * @copyright 2019-05-14 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

namespace availability_ipaddress;

use core_availability\info;

/**
 * Class condition
 *
 * @package   availability_ipaddress
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2019-05-14 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */
class condition extends \core_availability\condition {

    /**
     * Manual provided IP addresses.
     *
     * @var string
     */
    protected string $ipaddresses = '';

    /**
     * Predefined IP address ranges.
     *
     * @var array
     */
    protected array $predefinedranges = [];

    /**
     * condition constructor.
     *
     * @param \stdClass $structure
     */
    public function __construct(\stdClass $structure) {
        if (isset($structure->ipaddresses)) {
            $this->ipaddresses = $structure->ipaddresses;
        }
        if (isset($structure->predefined_ranges)) {
            $this->predefinedranges = $structure->predefined_ranges;
        }
    }

    /**
     * Determines whether a particular item is currently available
     * according to this availability condition.
     *
     * Note: Cannot add type declarations for $not, $grabthelot, and $userid parameters
     * as the parent core_availability\condition::is_available() method doesn't have them,
     * and PHP requires compatibility with parent method signatures when overriding.
     *
     * If implementations require a course or modinfo, they should use
     * the get methods in $info.
     *
     * The $not option is potentially confusing. This option always indicates
     * the 'real' value of NOT. For example, a condition inside a 'NOT AND'
     * group will get this called with $not = true, but if you put another
     * 'NOT OR' group inside the first group, then a condition inside that will
     * be called with $not = false. We need to use the real values, rather than
     * the more natural use of the current value at this point inside the tree,
     * so that the information displayed to users makes sense.
     *
     * @param bool $not        Set true if we are inverting the condition
     * @param info $info       Item we're checking
     * @param bool $grabthelot Performance hint: if true, caches information
     *                         required for all course-modules, to make the front page and similar
     *                         pages work more quickly (works only for current user)
     * @param int $userid      User ID to check availability for
     *
     * @return bool True if available
     */
    public function is_available($not, info $info, $grabthelot, $userid): bool {
        global $DB;

        // Collect all IP addresses to check.
        $allipaddresses = [];

        // Add custom IP addresses.
        if (!empty($this->ipaddresses)) {
            $allipaddresses[] = trim($this->ipaddresses);
        }

        // Add predefined ranges.
        if (!empty($this->predefinedranges)) {
            $ranges = $DB->get_records_list(
                'availability_ipaddress_pre',
                'id',
                $this->predefinedranges,
                '',
                'ipaddresses'
            );
            foreach ($ranges as $range) {
                if (!empty($range->ipaddresses)) {
                    $allipaddresses[] = trim($range->ipaddresses);
                }
            }
        }

        // If no IP addresses are configured, the condition passes.
        if (empty($allipaddresses)) {
            return !$not;
        }

        // Check if user's IP matches any of the allowed addresses.
        $userip = getremoteaddr();
        foreach ($allipaddresses as $iplist) {
            if (address_in_subnet($userip, $iplist)) {
                return !$not;
            }
        }

        return $not;
    }

    /**
     * Obtains a string describing this restriction (whether or not
     * it actually applies). Used to obtain information that is displayed to
     * students if the activity is not available to them, and for staff to see
     * what conditions are.
     *
     * Note: Cannot add type declarations for $full and $not parameters as the parent
     * core_availability\condition::get_description() method doesn't have them.
     *
     * The $full parameter can be used to distinguish between 'staff' cases
     * (when displaying all information about the activity) and 'student' cases
     * (when displaying only conditions they don't meet).
     *
     * If implementations require a course or modinfo, they should use
     * the get methods in $info.
     *
     * The special string <AVAILABILITY_CMNAME_123/> can be returned, where
     * 123 is any number. It will be replaced with the correctly-formatted
     * name for that activity.
     *
     * @param bool $full Set true if this is the 'full information' view
     * @param bool $not  Set true if we are inverting the condition
     * @param info $info Item we're checking
     *
     * @return string Information string (for admin) about all restrictions on this item
     */
    public function get_description($full, $not, info $info): string {

        $desc = $not ? 'require_condition_not' : 'require_condition';

        return get_string($desc, 'availability_ipaddress', getremoteaddr());
    }

    /**
     * Obtains a representation of the options of this condition as a string,
     * for debugging.
     *
     * @return string Text representation of parameters
     */
    protected function get_debug_string(): string {
        return !empty($this->ipaddresses) ? 'ipaddresses ON' : 'ipaddresses OFF';
    }

    /**
     * Returns a JSON object which corresponds to a condition of this type.
     *
     * Intended for unit testing, as normally the JSON values are constructed
     * by JavaScript code.
     *
     * @param string $ipaddresses
     *
     * @return \stdClass Object representing condition
     */
    public static function get_json(string $ipaddresses): \stdClass {
        return (object) [
            'type' => 'ipaddress',
            'ipaddresses' => $ipaddresses,
        ];
    }

    /**
     * Saves tree data back to a structure object.
     *
     * @return \stdClass Structure object (ready to be made into JSON format)
     */
    public function save(): \stdClass {
        $result = (object) [
            'type' => 'ipaddress',
            'ipaddresses' => $this->ipaddresses,
        ];

        if (!empty($this->predefinedranges)) {
            $result->predefined_ranges = $this->predefinedranges;
        }

        return $result;
    }

}
