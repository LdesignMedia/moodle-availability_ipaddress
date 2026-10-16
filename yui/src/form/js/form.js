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

// A single IPv4 octet (0-255) without leading zeros.
M.availability_ipaddress.octet = '(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]?\\d)';

// MIT https://github.com/sindresorhus/ip-regex
// IPv6 address, without anchors so it can be combined.
M.availability_ipaddress.v6 = "((?:[a-fA-F\\d]{1,4}:){7}(?:[a-fA-F\\d]{1,4}|:)|(?:[a-fA-F\\d]{1,4}:){6}(?:(?:25[0-5]|2[0-4]" +
    "\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|:[a-fA-F\\d]{1,4}|:)|(?:[a-fA-F\\d]{1,4}:){5}" +
    "(?::(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4})" +
    "{1,2}|:)|(?:[a-fA-F\\d]{1,4}:){4}(?:(:[a-fA-F\\d]{1,4}){0,1}:(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]" +
    "|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,3}|:)|(?:[a-fA-F\\d]{1,4}:){3}(?:(:[a-fA-F\\d]{1,4}){0,2}:" +
    "(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,4}|:)|" +
    "(?:[a-fA-F\\d]{1,4}:){2}(?:(:[a-fA-F\\d]{1,4}){0,3}:(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]" +
    "\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,5}|:)|(?:[a-fA-F\\d]{1,4}:){1}(?:(:[a-fA-F\\d]{1,4}){0,4}:(?:25[0-5]" +
    "|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}|(:[a-fA-F\\d]{1,4}){1,6}|:)|(?::" +
    "((?::[a-fA-F\\d]{1,4}){0,5}:(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)(?:\\.(?:25[0-5]|2[0-4]\\d|1\\d\\d|[1-9]\\d|\\d)){3}" +
    "|(?::[a-fA-F\\d]{1,4}){1,7}|:)))";

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
 * Check if a value is a valid IPv4 address.
 *
 * @param {string} value
 * @returns {boolean}
 */
M.availability_ipaddress.isIpv4 = function(value) {
    'use strict';
    var octet = M.availability_ipaddress.octet;
    return new RegExp('^' + octet + '(?:\\.' + octet + '){3}$').test(value);
};

/**
 * Check if a value is a valid IPv6 address that Moodle's cleanremoteaddr() can parse.
 *
 * cleanremoteaddr() doesn't handle embedded IPv4 notation reliably and only expands "::"
 * when it replaces at least two groups, or three at the start or end of the address.
 *
 * @param {string} value
 * @returns {boolean}
 */
M.availability_ipaddress.isIpv6 = function(value) {
    'use strict';
    var groups;

    if (value.indexOf('.') !== -1 || !new RegExp('^' + M.availability_ipaddress.v6 + '$').test(value)) {
        return false;
    }
    if (value.indexOf('::') === -1) {
        return true;
    }

    groups = value.split(':').filter(function(group) {
        return group !== '';
    }).length;
    if (value.indexOf('::') === 0 || value.slice(-2) === '::') {
        return groups <= 5;
    }
    return groups <= 6;
};

/**
 * Trim the same characters as PHP's trim(), which address_in_subnet() uses.
 *
 * @param {string} value
 * @returns {string}
 */
M.availability_ipaddress.trim = function(value) {
    'use strict';
    var chars = ' \t\n\r\x00\x0B',
        start = 0,
        end = value.length;

    while (start < end && chars.indexOf(value.charAt(start)) !== -1) {
        start++;
    }
    while (end > start && chars.indexOf(value.charAt(end - 1)) !== -1) {
        end--;
    }
    return value.substring(start, end);
};

/**
 * Validate a single entry. Only formats that Moodle's address_in_subnet() understands are
 * accepted, anything else would be silently ignored when the restriction is checked.
 *
 * - Address: 192.168.1.1 or 2001:db8::1
 * - Subnet: 192.168.1.0/24 or 2001:db8::/32
 * - Range in the last group: 192.168.1.1-255 or 2001:db8::1-ff
 *
 * @param {string} entry
 * @returns {boolean}
 */
M.availability_ipaddress.validateEntry = function(entry) {
    'use strict';
    var parts, mask, lastgroup;

    if (entry.indexOf('/') !== -1) {
        parts = entry.split('/');
        // Moodle's is_number() rejects a mask with leading zeros.
        if (parts.length !== 2 || !/^(0|[1-9]\d{0,2})$/.test(parts[1])) {
            return false;
        }
        mask = parseInt(parts[1], 10);
        if (M.availability_ipaddress.isIpv4(parts[0])) {
            return mask <= 32;
        }
        return M.availability_ipaddress.isIpv6(parts[0]) && mask <= 128;
    }

    if (entry.indexOf('-') !== -1) {
        parts = entry.split('-');
        if (parts.length !== 2) {
            return false;
        }

        // The end of the range may not be lower than the start, it would never match.
        if (M.availability_ipaddress.isIpv4(parts[0])) {
            return new RegExp('^' + M.availability_ipaddress.octet + '$').test(parts[1]) &&
                parseInt(parts[1], 10) >= parseInt(parts[0].split('.')[3], 10);
        }
        if (M.availability_ipaddress.isIpv6(parts[0])) {
            lastgroup = parts[0].split(':').pop() || '0';
            return /^[a-fA-F\d]{1,4}$/.test(parts[1]) && parseInt(parts[1], 16) >= parseInt(lastgroup, 16);
        }
        return false;
    }

    // An address ending in ":" is treated as an incomplete prefix by address_in_subnet(), which fails to parse it.
    return M.availability_ipaddress.isIpv4(entry) || (M.availability_ipaddress.isIpv6(entry) && entry.slice(-1) !== ':');
};

/**
 * Validate a comma separated list of ipaddresses.
 *
 * @param {string} ipaddresses
 * @returns {boolean}
 */
M.availability_ipaddress.validateIpaddress = function(ipaddresses) {
    'use strict';
    var entries, i;

    // Return true for empty string - it's valid to have no custom IPs.
    if (!ipaddresses || M.availability_ipaddress.trim(ipaddresses) === '') {
        return true;
    }

    entries = ipaddresses.split(',');
    for (i = 0; i < entries.length; i++) {
        // Whitespace around an entry is allowed, address_in_subnet() trims it as well.
        if (!M.availability_ipaddress.validateEntry(M.availability_ipaddress.trim(entries[i]))) {
            Y.log('Incorrect ip-address: ' + entries[i]);
            return false;
        }
    }

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

    // Basic ipaddresses checks - an empty value is valid.
    if (M.availability_ipaddress.validateIpaddress(value.ipaddresses) === false) {
        errors.push('availability_ipaddress:error_ipaddress');
    }
};
