## Moodle - availability ip address plugin
Enhance activity security by restricting access based on IP address. This plugin allows you to control the availability of any chosen
activity, making it accessible only to users from specified IP addresses.

## Author
![MFreak.nl](https://MFreak.nl/logo_small.png)

* Author: Luuk Verhoeven, [ldesignmedia.nl](https://ldesignmedia.nl/)
* Min. required: Moodle 4.0
* Supports PHP: 7.4

![Moodle400](https://img.shields.io/badge/moodle-4.0-brightgreen.svg?logo=moodle)
![Moodle401](https://img.shields.io/badge/moodle-4.1-brightgreen.svg?logo=moodle)
![Moodle402](https://img.shields.io/badge/moodle-4.2-brightgreen.svg?logo=moodle)
![Moodle403](https://img.shields.io/badge/moodle-4.3-brightgreen.svg?logo=moodle)
![Moodle404](https://img.shields.io/badge/moodle-4.4-brightgreen.svg?logo=moodle)
![Moodle405](https://img.shields.io/badge/moodle-4.5-brightgreen.svg?logo=moodle)

![PHP7.4](https://img.shields.io/badge/PHP-7.4-brightgreen.svg?logo=php)
![PHP8.0](https://img.shields.io/badge/PHP-8.0-brightgreen.svg?logo=php)
![PHP8.1](https://img.shields.io/badge/PHP-8.1-brightgreen.svg?logo=php)

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

If you discover any security related issues, please email [luuk@ldesignmedia.nl](mailto:luuk@ldesignmedia.nl) instead of using the issue tracker.

## License

The GNU GENERAL PUBLIC LICENSE. Please see [License File](LICENSE) for more information.

## Contributing

Contributions are welcome and will be fully credited. We accept contributions via Pull Requests on Github.

## Changelog

- 2025040400 Tested on Moodle 4.5
- 2024072000 Tested on Moodle 4.4
- 2022021100 Thanks for adding ip-range support @[juacas](https://github.com/juacas)
- 2022052800 Fixed the [issue 6](https://github.com/ldesignmediaNL/moodle-availability_ipaddress/issues/6) @[hamzatamyachte](https://github.com/hamzatamyachte)
- 2022052801 Test in Moodle 4.0 @[hamzatamyachte](https://github.com/hamzatamyachte)
