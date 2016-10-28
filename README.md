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

## Requirements

* PHP >= 5.6 with XML and SQLite3 support

## Installation

*This software is still highly experimental and comes entirely without support!*

These instructions have only been tested on Ubuntu 16.04. They should in general work for most Debian-based 
distributions, but the exact package names for PHP might vary.

Run the following commands, one by one, as `root`:

```bash
apt-get install -y curl git-core php7.0-cli php7.0-sqlite php7.0-xml unzip
mkdir -p /opt/tvheadend-status-manager
git clone https://github.com/Jalle19/tvheadend-status-manager.git /opt/tvheadend-status-manager
cd /opt/tvheadend-status-manager
curl -sS https://getcomposer.org/installer | php
php composer.phar install
php vendor/bin/propel sql:insert
php vendor/bin/propel migration:migrate
cp app/config.yml.example app/config.yml
cp app/settings.js.example src/client/app/js/settings.js
``` 

### Configuration

To successfully use the application you'll have to modify two files.

First, edit `app/config.yml`. You will at the very least need to specify which instances the application should 
monitor. It is also highly recommended to change the credentials for the web interface.

Second, edit `src/client/app/js/settings.js` so that the parameters match the ones you've used in `app/config.yml`. 

### Running the application

Run the application using `php ./app/tvheadend-status-manager app/config.yml -vv`. See the [Usage](#usage) section for 
more details on running it.

Assuming you haven't changed the listening address and port in `app/config.yml` you can now access the web interface on 
`http://<ip_address>:8080`.

## Setting up a development environment

1. Clone the repository
2. Run `vagrant up`
3. Run `vagrant ssh`
4. Run `./vendor/bin/propel sql:insert && ./vendor/bin/propel migration:migrate` to create the initial database
4. Copy `app/config.yml.example` to `app/config.yml` and configure it according to your environment. Most of the 
default values are sensible, but you'll need to configure your instances at least.
5. Copy `app/settings.js.example` to `src/client/app/js/settings.js` and configure it to contain the same values as 
you used for `app/config.yml`
6. Start the CLI application with `./app/tvheadend-status-manager app/config.yml`. You may want to 
add `-vv` (or even `-vvv`) to the command to get more output in the console. See the Usage section below for additional 
parameters
7. Browse to `http://192.168.47.47:8080/` to use the web interface (change the port if you changed it in 
your `config.yml`)

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
