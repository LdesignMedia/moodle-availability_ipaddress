## Moodle - availability ip address plugin
Restrict access to any activity by ip-address. This plugin can be used to make any chosen activity unavailable based on the user's IP.


## Author
![MFreak.nl](http://MFreak.nl/logo_small.png)

* Author: Luuk Verhoeven, [MFreak.nl](https://MFreak.nl/)
* Min. required: Moodle 3.5.x
* Supports PHP: 7.2

[![Build Status](https://travis-ci.org/MFreakNL/moodle-availability_ipaddress.svg?branch=master)](https://travis-ci.org/MFreakNL/moodle-availability_ipaddress)
![Moodle35](https://img.shields.io/badge/moodle-3.5-brightgreen.svg)
![Moodle36](https://img.shields.io/badge/moodle-3.6-brightgreen.svg)
![Moodle37](https://img.shields.io/badge/moodle-3.7-brightgreen.svg)
![Moodle38](https://img.shields.io/badge/moodle-3.8-brightgreen.svg)
![Moodle39](https://img.shields.io/badge/moodle-3.9-brightgreen.svg)
![Moodle310](https://img.shields.io/badge/moodle-3.10-brightgreen.svg)
![PHP7.2](https://img.shields.io/badge/PHP-7.2-brightgreen.svg)

![Screenshot](https://moodle.org/pluginfile.php/50/local_plugins/plugin_screenshots/2292/2019-05-15_11-01-39.png)

## List of features
- Supports comma separate list of ip-addresses
- Subnet support, eg 192.168.1.0/24
- Inline ip-address validation
- Turning on/off with eye icon, without lossing the input value. 

## Installation
1.  Copy this plugin to the `availability\condition\ipaddress` folder on the server
2.  Login as administrator
3.  Go to Site Administrator > Notification
4.  Install the plugin


## Usage

1. Add or edit an activity in a Moodle course.
2. Go to the section "Restrict access"
3. Click IP address in the modal
4. There's a new input field that supports a list of comma separated ip address e.g. 127.0.0.1, 192.168.1.0/24
   1. The users with matching ip addresses can view the activity.
5. Save the activity

## TODO 
- Behat tests ip validation

## Security

If you discover any security related issues, please email [luuk@MFreak.nl](mailto:luuk@MFreak.nl) instead of using the issue tracker.

## License

The GNU GENERAL PUBLIC LICENSE. Please see [License File](LICENSE) for more information.

## Contributing

Contributions are welcome and will be fully credited. We accept contributions via Pull Requests on Github.

## Changelog

- 2022021100 Thanks for adding ip-range support @[juacas](https://github.com/juacas)
