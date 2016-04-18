# tvheadend-status-manager

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/?branch=master) 
[![Code Climate](https://codeclimate.com/github/Jalle19/tvheadend-status-manager/badges/gpa.svg)](https://codeclimate.com/github/Jalle19/tvheadend-status-manager) 
[![Code Coverage](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/?branch=master)

*This software is still highly experimental and comes entirely without support!*

This project aims to provide a complete status and statistics manager for multiple tvheadend instances. It stores 
subscription status in a database in order to gather statistics about TV watching habits (such as the most popular 
channels, which users watch the most etc.). It also provides a realtime web interface which aggregates the streaming 
status from all configurd instances on a single web page.

The project consists of two separate pieces of software:
 
* a CLI application which polls each configured instance for status updates, then stores them in a database and 
broadcasts these updates to potential clients over a Websocket
* a web interface which connects to the Websocket and provides real-time status updates from all configured instances

## Setting up a development environment

1. Clone the repository
2. Run `vagrant up`
3. Run `vagrant ssh`
4. Run `./vendor/bin/propel sql:insert && ./vendor/bin/propel migration:migrate` to create the initial database
4. Copy `app/config.yml.example` to `app/config.yml` and configure your instances
5. Start the CLI application with `./app/tvheadend-status-manager app/config.yml app/database.sqlite`. You may want to 
add `-vv` (or even `-vvv`) to the command to get more output in the console. See the Usage section below for additional 
parameters
6. Browse to `http://192.168.47.47/` to use the web interface

## Usage

```
./app/tvheadend-status-manager --help
Usage:
  tvheadend-status-manager [options] [--] <configFile> <databaseFile> [<logFile>]

Arguments:
  configFile                           The path to the configuration file
  databaseFile                         The path to the database
  logFile                              The path to the log file

Options:
  -i, --updateInterval=UPDATEINTERVAL  The status update interval (in seconds) [default: 1]
  -l, --listenAddress=LISTENADDRESS    The address the Websocket server should be listening on [default: "0.0.0.0"]
  -p, --listenPort=LISTENPORT          The port the Websocket server should be listening on [default: 9333]
  -h, --help                           Display this help message
  -q, --quiet                          Do not output any message
  -V, --version                        Display this application version
      --ansi                           Force ANSI output
      --no-ansi                        Disable ANSI output
  -n, --no-interaction                 Do not ask any interactive question
  -v|vv|vvv, --verbose                 Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

## License

This application licensed under the GNU General Public License version 2
