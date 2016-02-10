# tvheadend-status-manager

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Jalle19/tvheadend-status-manager/?branch=master)

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
4. Copy `app/database.sqlite.example` to `app/database.sqlite`
4. Copy `app/config.ini.example` to `app/config.ini` and configure your instances
5. Start the CLI application with `./app/tvheadend-status-manager app/config.ini app/database.sqlite`. You may want to 
add `-vv` (or even `-vvv`) to the command to get more output in the console.
6. Browse to `http://192.168.47.47/` to use the web interface

## License

This application licensed under the GNU General Public License version 2
