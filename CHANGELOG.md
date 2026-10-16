# Changelog

## 5.3.2 (2026101500)
- Fixed IP address validation being too permissive https://github.com/LdesignMedia/moodle-availability_ipaddress/issues/2
  - Invalid entries like `2001:db8::1garbage` or `192.168.1.0/24garbage` are no longer accepted. Moodle silently ignored them, so a "must not" restriction could let blocked users in.
  - Only formats Moodle can match are accepted. Rejected: IPv6 written with an IPv4 part (`::ffff:192.168.1.1`), an IPv6 prefix without mask (`2001:db8::`, use `2001:db8::/32`), masks with leading zeros (`/024`) and some compressed IPv6 forms Moodle can't parse (`1::2:3:4:5:6:7`).
  - Predefined IP ranges are now validated with the same rules (subnet mask range, range end).
  - Spaces after commas are now allowed in the restriction field, e.g. `192.168.1.1, 10.0.0.1`.
  - Added support for IPv6 ranges in the last group, e.g. `2001:db8::1-ff`.
  - Note: existing restrictions and predefined ranges are not changed. If one contains an invalid entry, it now shows a validation error the next time it is edited. Entries that were already invalid never matched.

## 5.3.1 (2026101400)
- Solved CI tests https://github.com/LdesignMedia/moodle-availability_ipaddress/issues/20 | Thanks @minhduchoang195

## 5.3.0 (2026092300)
- Tested and verified on Moodle 5.3

## 5.2.0 (2026031802)
- Tested and verified on Moodle 5.2

## 5.1.1 (2026031800)
* Fixed PHP 7.3 compatibility issue that broke Moodle 4.0.x servers ([#13](https://github.com/LdesignMedia/moodle-availability_ipaddress/issues/13))
* Removed typed properties (PHP 7.4+) to support PHP 7.3+
* Lowered environment.xml PHP requirement from 7.4 to 7.3

## 5.1.0 (2026030300)
- Tested and verified on Moodle 5.1

## 5.0.2 (2025080401)
- Added support for pre-configuring IP ranges by admins

## 5.0.1 (2025052200)
- Tested on Moodle 5.0

## 4.5.0 (2025040400)
- Tested on Moodle 4.5

## 4.4.0 (2024072000)
- Tested on Moodle 4.4

## 4.0.3 (2022052801)
- Test in Moodle 4.0 @[hamzatamyachte](https://github.com/hamzatamyachte)

## 4.0.2 (2022052800)
- Fixed the [issue 6](https://github.com/ldesignmediaNL/moodle-availability_ipaddress/issues/6) @[hamzatamyachte](https://github.com/hamzatamyachte)

## 3.9.0 (2022021100)
- Thanks for adding ip-range support @[juacas](https://github.com/juacas)
