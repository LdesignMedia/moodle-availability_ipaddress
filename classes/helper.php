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
 * Helper functions for availability_ipaddress.
 *
 * @package    availability_ipaddress
 * @copyright  04/08/2025 LdesignMedia.nl - Luuk Verhoeven
 * @author     Vincent Cornelis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_ipaddress;

/**
 * Helper class for availability_ipaddress.
 *
 * @package    availability_ipaddress
 * @copyright  04/08/2025 LdesignMedia.nl - Luuk Verhoeven
 * @author     Vincent Cornelis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {

    /**
     * Check if a predefined range is in use.
     *
     * @param int $rangeid The ID of the range to check.
     *
     * @return array Array with 'inuse' boolean and 'count' of uses.
     */
    public static function is_range_in_use(int $rangeid): array {
        $activities = [];

        // Check course modules.
        $moduleactivities = self::check_range_in_modules($rangeid);
        $activities = array_merge($activities, $moduleactivities);

        // Check sections.
        $sectionactivities = self::check_range_in_sections($rangeid);
        $activities = array_merge($activities, $sectionactivities);

        $count = count($activities);

        return [
            'inuse' => ($count > 0),
            'count' => $count,
            'activities' => $activities,
        ];
    }

    /**
     * Check if a range is used in course modules.
     *
     * @param int $rangeid The ID of the range to check.
     *
     * @return array Array of activities using the range.
     */
    private static function check_range_in_modules(int $rangeid): array {
        global $DB;

        $activities = [];

        $sql = "SELECT cm.id, cm.course, cm.availability, cm.module, cm.instance,
                       c.fullname as coursename, m.name as modname
                FROM {course_modules} cm
                JOIN {course} c ON c.id = cm.course
                JOIN {modules} m ON m.id = cm.module
                WHERE cm.availability IS NOT NULL AND cm.availability != ''";

        $modules = $DB->get_records_sql($sql);

        foreach ($modules as $module) {
            $availability = json_decode($module->availability);
            if (!$availability || !self::check_availability_tree($availability, $rangeid)) {
                continue;
            }

            $activityname = self::get_module_name($module);
            $activities[] = [
                'coursename' => $module->coursename,
                'cmid' => $module->id,
                'name' => $activityname,
            ];
        }

        return $activities;
    }

    /**
     * Get the name of a module.
     *
     * @param \stdClass $module The module record.
     *
     * @return string The module name.
     */
    private static function get_module_name(\stdClass $module): string {
        global $DB;

        try {
            if ($DB->get_manager()->table_exists($module->modname)) {
                $activity = $DB->get_record($module->modname, ['id' => $module->instance], 'name', IGNORE_MISSING);
                if ($activity && !empty($activity->name)) {
                    return $activity->name;
                }
            }
        } catch (\Exception $e) {
            // Table doesn't exist or other database error - fall through to default.
            debugging('Error getting module name: ' . $e->getMessage(), DEBUG_DEVELOPER);
        }

        return get_string('modulename', $module->modname);
    }

    /**
     * Check if a range is used in course sections.
     *
     * @param int $rangeid The ID of the range to check.
     *
     * @return array Array of sections using the range.
     */
    private static function check_range_in_sections(int $rangeid): array {
        global $DB;

        $activities = [];

        $sql = "SELECT cs.id, cs.course, cs.availability, cs.name, cs.section, c.fullname as coursename
                FROM {course_sections} cs
                JOIN {course} c ON c.id = cs.course
                WHERE cs.availability IS NOT NULL AND cs.availability != ''";

        $sections = $DB->get_records_sql($sql);

        foreach ($sections as $section) {
            $availability = json_decode($section->availability);
            if (!$availability || !self::check_availability_tree($availability, $rangeid)) {
                continue;
            }

            $sectionname = $section->name ?: get_string('section') . ' ' . $section->section;
            $activities[] = [
                'coursename' => $section->coursename,
                'cmid' => 0,
                'name' => $sectionname,
            ];
        }

        return $activities;
    }

    /**
     * Recursively check availability tree for range usage.
     *
     * @param \stdClass $availability The availability tree.
     * @param int $rangeid            The range ID to look for.
     *
     * @return bool True if range is found in tree.
     */
    private static function check_availability_tree(\stdClass $availability, int $rangeid): bool {

        // Check if this is an IP address condition.
        if (isset($availability->type) && $availability->type === 'ipaddress') {
            if (isset($availability->predefined_ranges) && is_array($availability->predefined_ranges)) {
                return in_array($rangeid, $availability->predefined_ranges);
            }
        }

        // Check nested conditions (for groups).
        if (isset($availability->c) && is_array($availability->c)) {
            foreach ($availability->c as $condition) {
                if (self::check_availability_tree($condition, $rangeid)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get usage details for a range as HTML.
     *
     * @param int $rangeid The range ID.
     *
     * @return string HTML string with usage details.
     */
    public static function get_range_usage_html(int $rangeid): string {
        $usage = self::is_range_in_use($rangeid);

        if (!$usage['inuse']) {
            return '';
        }

        $html = \html_writer::tag('p', get_string('range_in_use_count', 'availability_ipaddress', $usage['count']));

        if (!empty($usage['activities'])) {
            $items = [];
            foreach (array_slice($usage['activities'], 0, 5) as $activity) {
                $items[] = $activity['coursename'] . ': ' . $activity['name'];
            }
            $html .= \html_writer::alist($items);

            if (count($usage['activities']) > 5) {
                $more = count($usage['activities']) - 5;
                $html .= \html_writer::tag('p', get_string('and_x_more', 'availability_ipaddress', $more));
            }
        }

        return $html;
    }

    /**
     * Remove a predefined range from all availability restrictions.
     *
     * @param int $rangeid The ID of the range to remove.
     *
     * @return int Number of restrictions updated.
     */
    public static function remove_range_from_restrictions(int $rangeid): int {
        global $DB;

        $updatecount = 0;

        // Update course modules.
        $sql = "SELECT cm.id, cm.availability
                FROM {course_modules} cm
                WHERE cm.availability IS NOT NULL AND cm.availability != ''";

        $modules = $DB->get_records_sql($sql);

        foreach ($modules as $module) {
            $availability = json_decode($module->availability);
            if ($availability && self::remove_range_from_tree($availability, $rangeid)) {
                $module->availability = json_encode($availability);
                $DB->update_record('course_modules', $module);
                $updatecount++;

                // Rebuild course cache.
                $course = $DB->get_record('course', ['id' => $DB->get_field('course_modules', 'course', ['id' => $module->id])]);
                if ($course) {
                    rebuild_course_cache($course->id, true);
                }
            }
        }

        // Update course sections.
        $sql = "SELECT cs.id, cs.course, cs.availability
                FROM {course_sections} cs
                WHERE cs.availability IS NOT NULL AND cs.availability != ''";

        $sections = $DB->get_records_sql($sql);

        foreach ($sections as $section) {
            $availability = json_decode($section->availability);
            if ($availability && self::remove_range_from_tree($availability, $rangeid)) {
                $section->availability = json_encode($availability);
                $DB->update_record('course_sections', $section);
                $updatecount++;

                // Rebuild course cache.
                rebuild_course_cache($section->course, true);
            }
        }

        return $updatecount;
    }

    /**
     * Recursively remove range from availability tree.
     *
     * @param \stdClass $availability The availability tree.
     * @param int $rangeid            The range ID to remove.
     *
     * @return bool True if tree was modified.
     */
    private static function remove_range_from_tree(\stdClass $availability, int $rangeid): bool {
        $modified = false;

        // Process IP address conditions.
        if (self::is_ipaddress_condition($availability)) {
            $modified = self::remove_range_from_condition($availability, $rangeid);
        }

        // Process nested conditions.
        return self::process_nested_conditions($availability, $rangeid) || $modified;

    }

    /**
     * Check if the availability item is an IP address condition.
     *
     * @param \stdClass $availability The availability item.
     *
     * @return bool True if it's an IP address condition.
     */
    private static function is_ipaddress_condition(\stdClass $availability): bool {
        return isset($availability->type) && $availability->type === 'ipaddress';
    }

    /**
     * Remove range from an IP address condition.
     *
     * @param \stdClass $availability The availability condition.
     * @param int $rangeid            The range ID to remove.
     *
     * @return bool True if the condition was modified.
     */
    private static function remove_range_from_condition(\stdClass $availability, int $rangeid): bool {
        if (!isset($availability->predefined_ranges) || !is_array($availability->predefined_ranges)) {
            return false;
        }

        $key = array_search($rangeid, $availability->predefined_ranges);
        if ($key === false) {
            return false;
        }

        // Remove the range from the array.
        array_splice($availability->predefined_ranges, $key, 1);

        // If no ranges left, remove the predefined_ranges property.
        if (empty($availability->predefined_ranges)) {
            unset($availability->predefined_ranges);
        }

        return true;
    }

    /**
     * Process nested conditions in availability tree.
     *
     * @param \stdClass $availability The availability item.
     * @param int $rangeid            The range ID to remove.
     *
     * @return bool True if any nested condition was modified.
     */
    private static function process_nested_conditions(\stdClass $availability, int $rangeid): bool {
        if (!isset($availability->c) || !is_array($availability->c)) {
            return false;
        }

        $modified = false;
        foreach ($availability->c as $condition) {
            if (self::remove_range_from_tree($condition, $rangeid)) {
                $modified = true;
            }
        }

        return $modified;
    }

}
