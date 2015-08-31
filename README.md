# tvheadend-status-manager

*This software is still highly experimental and comes entirely without support!*

This project provides a web interface to manage the streaming status of multiple tvheadend instances. This is done 
using two applications:
 
* a CLI application which polls each configured instance for status updates and then broadcasts these 
updates to potential clients over a Websocket.
* a web interface which connects to the Websocket and provides real-time status updates from all configured instances

## Setting up a development environment

1. Clone the repository
2. Run `vagrant up`
3. Run `vagrant ssh`
4. Copy `app/config.ini.example` to `app/config.ini` and configure your instances
5. Start the CLI application with `./app/tvheadend-status-manager app/config.ini`
6. Browse to `http://192.168.47.47/`

## License

This application licensed under the GNU General Public License version 2
