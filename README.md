# tvheadend-status-manager

[![Build Status](https://travis-ci.org/Jalle19/tvheadend-status-manager.svg?branch=master)](https://travis-ci.org/Jalle19/tvheadend-status-manager) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/?branch=master) 
[![Code Climate](https://codeclimate.com/github/Jalle19/tvheadend-status-manager/badges/gpa.svg)](https://codeclimate.com/github/Jalle19/tvheadend-status-manager) 
[![Code Coverage](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/?branch=master)

*This software is still highly experimental and comes entirely without support!*

This project aims to provide a complete status and statistics manager for multiple tvheadend instances. It stores 
subscription statuses in a database in order to gather statistics about TV watching habits (such as the most popular 
channels, which users watch the most etc.). It also provides a real-time web interface which aggregates the streaming 
status from all configured instances on a single web page.

The project consists of two separate pieces of software:
 
* a daemon which polls each configured instance for status updates, then stores them in a database. The daemon can be 
interacted with using a WebSocket
* a web interface which connects to the daemon and provides real-time status updates and statistics from all configured 
instances

## Setting up a development environment

1. Clone the repository
2. Run `vagrant up`
3. Run `vagrant ssh`
4. Run `./vendor/bin/propel sql:insert && ./vendor/bin/propel migration:migrate` to create the initial database
4. Copy `app/config.yml.example` to `app/config.yml` and configure it according to your environment. Most of the 
default values are sensible, but you'll need to configure your instances at least.
5. Start the CLI application with `./app/tvheadend-status-manager app/config.yml`. You may want to 
add `-vv` (or even `-vvv`) to the command to get more output in the console. See the Usage section below for additional 
parameters
6. Browse to `http://192.168.47.47/` to use the web interface

### Running tests

To run the test suite, run `./vendor/bin/phpunit` from the project root directory. To generate code statistics, run 
`./vendor/bin/phploc --exclude=Database/Base --exclude=Database/Map src/cli`.

## Usage

```
./app/tvheadend-status-manager --help
Usage:
  tvheadend-status-manager <configFile>

Arguments:
  configFile            The path to the configuration file

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
 Aggregating status manager for tvheadend instances
```

## License

This application licensed under the GNU General Public License version 2
