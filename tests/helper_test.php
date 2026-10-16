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

namespace availability_ipaddress;

/**
 * Unit tests for the IP address validation in the helper class.
 *
 * @package   availability_ipaddress
 * @copyright 2026 LdesignMedia.nl - Luuk Verhoeven
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers    \availability_ipaddress\helper
 */
final class helper_test extends \basic_testcase {
    /**
     * Data provider for test_is_valid_ipaddress_list.
     *
     * @return array
     */
    public static function ipaddress_list_provider(): array {
        return [
            'IPv4 address' => ['192.168.1.1', true],
            'IPv4 subnet' => ['192.168.1.0/24', true],
            'IPv4 subnet all addresses' => ['0.0.0.0/0', true],
            'IPv4 range' => ['192.168.1.10-20', true],
            'IPv4 range single address' => ['192.168.1.5-5', true],
            'IPv6 address' => ['2001:db8::1', true],
            'IPv6 loopback' => ['::1', true],
            'IPv6 full address' => ['2001:db8:0:0:0:0:0:1', true],
            'IPv6 compressed in the middle' => ['1:2:3::4:5:6', true],
            'IPv6 compressed at the start' => ['::1:2:3:4:5', true],
            'IPv6 subnet' => ['2001:db8::/32', true],
            'IPv6 subnet all addresses' => ['::/0', true],
            'IPv6 range' => ['2001:db8::1-ff', true],
            'IPv6 range from compressed start' => ['2001:db8::-ffff', true],
            'List' => ['192.168.1.1,10.0.0.0/8,2001:db8::/32', true],
            'List with spaces' => [' 192.168.1.1 , 10.0.0.1 ', true],
            'IPv6 with trailing text' => ['2001:db8::1garbage', false],
            'IPv6 with zone id' => ['fe80::1%eth0', false],
            'IPv6 subnet mask too large' => ['2001:db8::/129', false],
            'IPv6 address ending in ::' => ['2001:db8::', false],
            'IPv6 unspecified address' => ['::', false],
            'IPv6 :: replacing one group' => ['1::2:3:4:5:6:7', false],
            'IPv6 :: at the start replacing two groups' => ['::1:2:3:4:5:6', false],
            'IPv6 subnet :: at the end replacing two groups' => ['2001:db8:1:2:3:4::/112', false],
            'IPv6 with embedded IPv4' => ['::ffff:192.168.1.1', false],
            'IPv6 subnet with embedded IPv4' => ['::ffff:192.168.1.0/120', false],
            'IPv4 subnet with trailing text' => ['192.168.1.0/24garbage', false],
            'IPv4 subnet mask too large' => ['192.168.1.0/33', false],
            'IPv4 subnet mask with leading zero' => ['192.168.1.0/024', false],
            'IPv4 subnet negative mask' => ['192.168.1.0/-5', false],
            'IPv4 subnet exponent mask' => ['192.168.1.0/1e3', false],
            'IPv4 subnet trailing newline' => ["192.168.1.0/24\n", true],
            'IPv4 with non-breaking space' => ["192.168.1.1\u{a0}", false],
            'IPv4 subnet double mask' => ['192.168.1.0/24/8', false],
            'IPv4 range with text' => ['192.168.1.1-garbage', false],
            'IPv4 range end too large' => ['192.168.1.1-256', false],
            'IPv4 range end below start' => ['192.168.1.20-10', false],
            'IPv4 range double dash' => ['192.168.1.1-2-3', false],
            'IPv6 range end below start' => ['2001:db8::ff-1', false],
            'Invalid octet' => ['999.1.1.1', false],
            'Leading zero' => ['192.168.01.1', false],
            'Text' => ['abc', false],
            'Text with subnet' => ['hello/gm', false],
            'Empty' => ['', false],
            'Zero' => ['0', false],
            'Trailing comma' => ['192.168.1.1,', false],
            'List with one invalid entry' => ['192.168.1.1, 2001:db8::1garbage', false],
        ];
    }

    /**
     * Test validation of a comma separated list of IP addresses.
     *
     * @dataProvider ipaddress_list_provider
     *
     * @param string $ipaddresses
     * @param bool $expected
     *
     * @return void
     */
    public function test_is_valid_ipaddress_list(string $ipaddresses, bool $expected): void {
        $this->assertSame($expected, helper::is_valid_ipaddress_list($ipaddresses));
    }
}
