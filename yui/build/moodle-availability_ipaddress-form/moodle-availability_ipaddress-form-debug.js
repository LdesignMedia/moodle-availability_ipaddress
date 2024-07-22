YUI.add('moodle-availability_ipaddress-form', function (Y, NAME) {

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
 * Availability ip-address YUI code
 *
 * @package   availability_ipaddress
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2019-05-14 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */

/**
 * JavaScript for form editing grade conditions.
 *
 * @module moodle-availability_ipaddress-form
 */
M.availability_ipaddress = M.availability_ipaddress || {};

// MIT https://github.com/sindresorhus/ip-regex
// Advanced ip-address regex for validating.
M.availability_ipaddress.v4 = '(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])(?:\\.(?:25[0-5]|2[0-4][0-9]|1[0-9]' +
    '[0-9]|[1-9][0-9]|[0-9])){3}';

M.availability_ipaddress.v6 = "^((?:[a-fA-F\\d]{1,4}:){7}(?:[a-fA-F\\d]{1,4}|:)|(?:[a-fA-F\\d]{1,4}:){6}(?:(?:25[0-5]|2[0-4]" +
    "\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|:[a-fA-F\\d]{1,4}|:)|(?:[a-fA-F\\d]{1,4}:){5}" +
    "(?::(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4})" +
    "{1,2}|:)|(?:[a-fA-F\\d]{1,4}:){4}(?:(:[a-fA-F\\d]{1,4}){0,1}:(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]" +
    "|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,3}|:)|(?:[a-fA-F\\d]{1,4}:){3}(?:(:[a-fA-F\\d]{1,4}){0,2}:" +
    "(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,4}|:)|" +
    "(?:[a-fA-F\\d]{1,4}:){2}(?:(:[a-fA-F\\d]{1,4}){0,3}:(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]" +
    "\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,5}|:)|(?:[a-fA-F\\d]{1,4}:){1}(?:(:[a-fA-F\\d]{1,4}){0,4}:(?:25[0-5]" +
    "|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,6}|:)|(?::" +
    "((?::[a-fA-F\\d]{1,4}){0,5}:(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}" +
    "|(?::[a-fA-F\\d]{1,4}){1,7}|:)))(%[0-9a-zA-Z]{1,})?";

M.availability_ipaddress.ipv4Regex = "^(25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]?\\d)(\\.(25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]?\\d)){3}$";
M.availability_ipaddress.ipv4RangeRegex = "^(25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]?\\d)(\\.(25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]?\\d)){3}-"
    + "(25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]?\\d)$";

M.availability_ipaddress.ipv6Regex =
    "^(([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:)|([0-9A-Fa-f]{1,4}:){6}(:|[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4][0-9]|"
    + "[01]?[0-9][0-9]?)\\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|([0-9A-Fa-f]{1,4}:){5}((:[0-9A-Fa-f]{1,4}){1,2}|:|((:[0-9A-"
    + "Fa-f]{1,4})?:((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)))|([0-9A-Fa-f]{1,4}:){4}(("
    + ":[0-9A-Fa-f]{1,4}){1,3}|(:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-"
    + "9][0-9]?))|([0-9A-Fa-f]{1,4}:){3}((:[0-9A-Fa-f]{1,4}){1,4}|(:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)"
    + "\\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|([0-9A-Fa-f]{1,4}:){2}((:[0-9A-Fa-f]{1,4}){1,5}|(:[0-9A-Fa-f]{1,4}){0,4}:((2"
    + "5[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|([0-9A-Fa-f]{1,4}:){1}((:[0-9A-Fa-f]{1,4}"
    + "){1,6}|(:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))|:((:[0"
    + "-9A-Fa-f]{1,4}){1,7}|(:[0-9A-Fa-f]{1,4}){0,6}:((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9]["
    + "0-9]?)))(%.+)?$";

/**
 * @class M.availability_ipaddress.form
 * @extends M.core_availability.plugin
 */
M.availability_ipaddress.form = Y.Object(M.core_availability.plugin);

/**
 * Initialises this plugin.
 *
 * @method initInner
 */
M.availability_ipaddress.form.initInner = function() {
    "use strict";
    Y.log('M.availability_ipaddress 1.2', 'moodle-availability_ipaddress-form');
};

/**
 * Gets the numeric value of an input field. Supports decimal points (using
 * dot or comma).
 *
 * @method getValue
 * @param {string} field
 * @param {object} node
 * @return {Number|String} Value of field as number or string if not valid
 */
M.availability_ipaddress.form.getValue = function(field, node) {
    "use strict";
    // Get field value.
    var value = node.one('input[name=' + field + ']').get('value');
    Y.log('ip_address:' + value, 'moodle-availability_ipaddress-form');

    // If it is not a valid positive number, return false.
    if (M.availability_ipaddress.validateIpaddress(value)) {
        Y.log('Valid ip-address', 'moodle-availability_ipaddress-form');
        return value;
    }

    return value;
};

/**
 * Get node
 *
 * @param {object} json
 * @returns {*}
 */
M.availability_ipaddress.form.getNode = function(json) {
    "use strict";
    var html, node, root, id;

    // Make sure we work with unique id.
    id = 'ipaddresses' + M.availability_ipaddress.form.instId;
    M.availability_ipaddress.form.instId += 1;

    // Create HTML structure.
    html = '';
    html += '<span class="availability-group"><label for="' + id + '"><span class="p-r-1">' +
        M.util.get_string('title', 'availability_ipaddress') + ' </span></label>';
    html += '<input type="text" placeholder="192.168.178.1,231.54.211.0/20,231.3.56.211" name="ipaddresses" id="' + id + '">';
    node = Y.Node.create('<span class="form-inline">' + html + '</span>');

    // Set initial values, if specified.
    if (json.ipaddresses !== undefined) {
        node.one('input[name=ipaddresses]').set('value', json.ipaddresses);
    }

    // Add event handlers (first time only).
    if (!M.availability_ipaddress.form.addedEvents) {
        M.availability_ipaddress.form.addedEvents = true;
        root = Y.one('.availability-field');
        root.delegate('valuechange', function() {
            // Trigger the updating of the hidden availability data whenever the ipaddress field changes.
            M.core_availability.form.update();
        }, '.availability_ipaddress input[name=ipaddresses]');
    }

    return node;
};

/**
 * Validate ipaddresses
 *
 * @param {string[]} ipaddresses
 * @returns {boolean}
 */
M.availability_ipaddress.validateIpaddress = function(ipaddresses) {
    'use strict';

    ipaddresses = ipaddresses.split(',');

    // Define regex patterns for IPv4, IPv4 range, IPv6, and subnets

    var subnetRegex = new RegExp(
        "^(?:" + M.availability_ipaddress.v4 + "\\/(3[0-2]|[12]?[0-9])|(1\\*))|"
        + "(?:" + M.availability_ipaddress.v6 + "\\/(12[0-8]|1[01][0-9]|[1-9]?[0-9]))$"
    );

    for (var i = 0; i < ipaddresses.length; i++) {
        var ip = ipaddresses[i];

        if (new RegExp(M.availability_ipaddress.ipv4Regex).test(ip)) {
            Y.log('Correct IPv4: ' + ip, 'moodle-availability_ipaddress-form');
            continue;
        }

        if (new RegExp(M.availability_ipaddress.ipv4RangeRegex).test(ip)) {
            Y.log('Correct IPv4 range: ' + ip, 'moodle-availability_ipaddress-form');
            continue;
        }

        if (new RegExp(M.availability_ipaddress.ipv6Regex).test(ip)) {
            Y.log('Correct IPv6: ' + ip, 'moodle-availability_ipaddress-form');
            continue;
        }

        if (subnetRegex.test(ip)) {
            Y.log('Correct subnet: ' + ip, 'moodle-availability_ipaddress-form');
            continue;
        }

        Y.log('Incorrect IP: ' + ip, 'moodle-availability_ipaddress-form');
        return false;
    }

    Y.log('All IP addresses are valid', 'moodle-availability_ipaddress-form');
    return true;
};

/**
 * FillValue
 *
 * @param {object} value
 * @param {object} node
 */
M.availability_ipaddress.form.fillValue = function(value, node) {
    // This function gets passed the node (from above) and a value
    // object. Within that object, it must set up the correct values
    // to use within the JSON data in the form. Should be compatible
    // with the structure used in the __construct and save functions
    // within condition.php.
    value.ipaddresses = this.getValue('ipaddresses', node);
};

/**
 * FillErrors
 * @param {object} errors
 * @param {object} node
 */
M.availability_ipaddress.form.fillErrors = function(errors, node) {
    "use strict";
    var value = {};
    this.fillValue(value, node);

    // Basic ipaddresses checks.
    if (M.availability_ipaddress.validateIpaddress(value.ipaddresses) === false) {
        errors.push('availability_ipaddress:error_ipaddress');
    }
};


}, '@VERSION@', {"requires": ["base", "event", "node", "moodle-core_availability-form"]});
