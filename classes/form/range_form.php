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
 * Form for managing predefined IP ranges.
 *
 * @package    availability_ipaddress
 * @copyright  04/08/2025 LdesignMedia.nl - Luuk Verhoeven
 * @author     Vincent Cornelis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_ipaddress\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Form for managing predefined IP ranges.
 *
 * @package    availability_ipaddress
 * @copyright  04/08/2025 LdesignMedia.nl - Luuk Verhoeven
 * @author     Vincent Cornelis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class range_form extends \moodleform {

    /**
     * Define the form.
     *
     * @return void
     */
    protected function definition(): void {
        $mform = $this->_form;
        $id = $this->_customdata['id'] ?? 0;

        // Hidden id field.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $id);

        // Name field.
        $mform->addElement('text', 'name', get_string('range_name', 'availability_ipaddress'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addHelpButton('name', 'range_name', 'availability_ipaddress');

        // IP addresses field.
        $mform->addElement(
            'text',
            'ipaddresses',
            get_string('ipaddresses', 'availability_ipaddress'),
            ['size' => 100]
        );
        $mform->setType('ipaddresses', PARAM_TEXT);
        $mform->addRule('ipaddresses', null, 'required', null, 'client');
        $mform->addHelpButton('ipaddresses', 'ipaddresses_help', 'availability_ipaddress');

        // Description field.
        $mform->addElement('textarea', 'description', get_string('description'),
            ['rows' => 3, 'cols' => 60]);
        $mform->setType('description', PARAM_TEXT);

        // Enabled field.
        $mform->addElement('advcheckbox', 'enabled', get_string('enabled', 'availability_ipaddress'));
        $mform->setDefault('enabled', 1);

        // Action buttons.
        $this->add_action_buttons();
    }

    /**
     * Validate the form data.
     *
     * Note: Parameter type declarations cannot be added here as the parent
     * moodleform::validation() method doesn't have them, and PHP requires
     * compatibility with parent method signatures when overriding.
     *
     * @param array $data
     * @param array $files
     *
     * @return array
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Validate IP addresses.
        if (!empty($data['ipaddresses'])) {
            $ipaddresses = explode(',', $data['ipaddresses']);
            foreach ($ipaddresses as $ip) {
                $ip = trim($ip);
                if (!$this->validate_ip_format($ip)) {
                    $errors['ipaddresses'] = get_string('error_ipaddress', 'availability_ipaddress');
                    break;
                }
            }
        }

        return $errors;
    }

    /**
     * Validate IP address format.
     *
     * @param string $ip
     *
     * @return bool
     */
    private function validate_ip_format(string $ip): bool {

        // Use the same validation logic as the main plugin.
        // This is a simplified version - you might want to use the same regex as in JS.
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        }

        // Check for CIDR notation.
        if (strpos($ip, '/') !== false) {
            [$addr, $mask] = explode('/', $ip);
            if (filter_var($addr, FILTER_VALIDATE_IP) && is_numeric($mask)) {
                return true;
            }
        }

        // Check for IP range.
        if (strpos($ip, '-') !== false) {
            [$start, $end] = explode('-', $ip);
            if (filter_var($start, FILTER_VALIDATE_IP)) {
                return true;
            }
        }

        return false;
    }

}
