## Moodle - availability ip address plugin
Restrict access to any activity by ip-address. Most activities don't support this feature. 

## Author
![MFreak.nl](http://MFreak.nl/logo_small.png)

* Author: Luuk Verhoeven, [MFreak.nl](https://MFreak.nl/)
* Min. required: Moodle 3.2.x
* Supports PHP: 7.0 | 7.1 

[![Build Status](https://travis-ci.org/MFreakNL/moodle-availability_ipaddress.svg?branch=master)](https://travis-ci.org/MFreakNL/moodle-availability_ipaddress)

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

## TODO 
- Behat tests ip validation

## Security

If you discover any security related issues, please email [luuk@MFreak.nl](mailto:luuk@MFreak.nl) instead of using the issue tracker.

## License

The GNU GENERAL PUBLIC LICENSE. Please see [License File](LICENSE) for more information.

## Contributing

Contributions are welcome and will be fully credited. We accept contributions via Pull Requests on Github.
