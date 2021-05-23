#!/bin/bash

{
	add-apt-repository -y ppa:ondrej/php
	
	apt-get update
	apt-get -y install php7.0-cli php7.0-xdebug php7.0-xml php7.0-sqlite3 curl git-core unzip
	
	# clean up from older nodejs installations
	apt-get -y remove --purge nodejs* npm
	
	# install nodejs from repository
	curl -sL https://deb.nodesource.com/setup_6.x | bash -
	apt-get install -y nodejs

	cd /vagrant
	curl -sS https://getcomposer.org/installer | php

	npm install -g grunt-cli

} > /dev/null 2>&1
