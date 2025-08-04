YUI.add('moodle-availability_ipaddress-form', function (Y, NAME) {

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

/**
 * @class M.availability_ipaddress.form
 * @extends M.core_availability.plugin
 */
M.availability_ipaddress.form = Y.Object(M.core_availability.plugin);

/**
 * Initialises this plugin.
 *
 * @method initInner
 * @param {Array} param Array of objects
 */
M.availability_ipaddress.form.initInner = function(param) {
    "use strict";
    Y.log('M.availability_ipaddress 1.10');
    // Store predefined ranges from backend.
    this.predefinedRanges = param || [];
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
    Y.log('ip_address:' + value);

    // If it is not a valid positive number, return false.
    if (M.availability_ipaddress.validateIpaddress(value)) {
        Y.log('Valid ip-address');
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
    var html, node, root, id, selectId, i, range;

    // Make sure we work with unique id.
    id = 'ipaddresses' + M.availability_ipaddress.form.instId;
    selectId = 'predefined' + M.availability_ipaddress.form.instId;
    M.availability_ipaddress.form.instId += 1;

    // Create HTML structure.
    html = '<div class="availability-ipaddress-container">';

    // Add predefined ranges if available.
    if (this.predefinedRanges && this.predefinedRanges.length > 0) {
        html += '<div class="availability-group">';
        html += '<label><span class="p-r-1">' +
            M.util.get_string('use_predefined', 'availability_ipaddress') + '</span></label>';
        html += '<select name="predefined_ranges" id="' + selectId +
            '" multiple="multiple" class="form-control" style="min-height: 100px;">';

        for (i = 0; i < this.predefinedRanges.length; i++) {
            range = this.predefinedRanges[i];
            html += '<option value="' + range.id + '" data-ipaddresses="' + Y.Escape.html(range.ipaddresses) + '">';
            html += Y.Escape.html(range.name);
            html += '</option>';
        }

        html += '</select>';
        html += '</div>';

        html += '<div class="availability-group" style="margin-top: 10px;">';
        html += '<label for="' + id + '"><span class="p-r-1">' +
            M.util.get_string('custom_ipaddress', 'availability_ipaddress') + '</span></label>';
    } else {
        html += '<div class="availability-group">';
        html += '<label for="' + id + '"><span class="p-r-1">' +
            M.util.get_string('title', 'availability_ipaddress') + '</span></label>';
    }

    html += '<input type="text" placeholder="192.168.178.1,231.54.211.0/20,231.3.56.211" name="ipaddresses" id="' +
        id + '" class="form-control">';
    html += '</div>';
    html += '</div>';

    node = Y.Node.create('<div>' + html + '</div>');

    // Set initial values, if specified.
    if (json.ipaddresses !== undefined) {
        node.one('input[name=ipaddresses]').set('value', json.ipaddresses);
    }

    // Set selected predefined ranges if specified.
    if (json.predefined_ranges !== undefined && this.predefinedRanges && this.predefinedRanges.length > 0) {
        var select = node.one('select[name=predefined_ranges]');
        if (select) {
            json.predefined_ranges.forEach(function(rangeId) {
                var option = select.one('option[value="' + rangeId + '"]');
                if (option) {
                    option.set('selected', true);
                }
            });
        }
    }

    // Add event handlers (first time only).
    if (!M.availability_ipaddress.form.addedEvents) {
        M.availability_ipaddress.form.addedEvents = true;
        root = Y.one('.availability-field');
        root.delegate('valuechange', function() {
            // Trigger the updating of the hidden availability data whenever the ipaddress field changes.
            M.core_availability.form.update();
        }, '.availability_ipaddress input[name=ipaddresses]');

        root.delegate('change', function() {
            // Trigger the updating when predefined ranges are selected.
            M.core_availability.form.update();
        }, '.availability_ipaddress select[name=predefined_ranges]');
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
    Y.log(ipaddresses);
    // Return true for empty string - it's valid to have no custom IPs
    if (!ipaddresses || ipaddresses.trim() === '') {
        return true;
    }
    ipaddresses = ipaddresses.split(',');
    for (var i in ipaddresses) {

        // Test normal ip format.
        // Strict ipv4 check.
        if (new RegExp(/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/gm)
            .test(ipaddresses[i])) {
            Y.log('Correct ipv4');
            continue;
        }

        var ipv4Regex = new RegExp(
            '^(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)' +
            '(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}-' +
            '(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]?\\d)$',
            'gm'
        );

        if (ipv4Regex.test(ipaddresses[i])) {
            Y.log('Correct ipv4 range.');
            continue;
        }

        if (new RegExp(M.availability_ipaddress.v6)
            .test(ipaddresses[i])) {
            Y.log('Correct ipv6');
            continue;
        }

        // Test subnet with a regex.
        if (new RegExp("^(?:".concat(M.availability_ipaddress.v4 + "\\/(3[0-2]|[12]?[0-9])|(1\\*)", ")|(?:")
            .concat(M.availability_ipaddress.v6 + "\\/(12[0-8]|1[01][0-9]|[1-9]?[0-9])", ")?\\/gm"))
            .test(ipaddresses[i])) {
            Y.log('Correct subnet');
            continue;
        }

        Y.log('Incorrect ip');
        return false;
    }

    Y.log('Valid ipaddresses');
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

    // Get selected predefined ranges.
    var select = node.one('select[name=predefined_ranges]');
    if (select) {
        var selectedRanges = [];
        select.get('options').each(function(option) {
            if (option.get('selected')) {
                selectedRanges.push(parseInt(option.get('value')));
            }
        });
        if (selectedRanges.length > 0) {
            value.predefined_ranges = selectedRanges;
        }
    }
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

    // Basic ipaddresses checks - only validate if not empty.
    if (value.ipaddresses && value.ipaddresses.trim() !== '' &&
        M.availability_ipaddress.validateIpaddress(value.ipaddresses) === false) {
        errors.push('availability_ipaddress:error_ipaddress');
    }
};


}, '@VERSION@', {"requires": ["base", "node", "event", "moodle-core_availability-form"]});
