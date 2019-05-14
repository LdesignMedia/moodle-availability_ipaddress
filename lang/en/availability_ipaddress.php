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
$string['pluginname'] = 'IP address';
$string['title'] = 'IP address';
$string['description'] = 'Restrict access by ip-address or subnet';
$string['require_condition'] = 'Matching ip-address/subnet';

// Javascript strings.
$string['js:ipaddress'] = 'Require network address';
$string['js:turn-on-timestamps'] = '';
$string['js:turn-off-timestamps'] = '';
$string['js:enabled'] = '';

// Errors.
$string['error_ipaddress'] = 'Incorrect ip-address/subnet format';

$string['requiresubnet_help'] = 'Access may be restricted to particular subnets on the LAN or Internet by specifying a comma-separated list of partial or full IP address numbers. This can be useful for an invigilated (proctored) quiz, to ensure that only people in a certain location can access the quiz.';
