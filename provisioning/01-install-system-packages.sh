#!/bin/bash

{
	add-apt-repository -y ppa:ondrej/php
	
	# clean up from old installs that used the stock PHP packages
	if dpkg -s php5-common; then
		apt-get -y remove --purge php5*
		rm -rf /etc/php5
	fi
	
	apt-get update
	apt-get -y install php5.6-cli php5.6-xdebug php5.6-xml php5.6-sqlite3 curl nginx nodejs nodejs-legacy npm git-core

	cd /vagrant
	curl -sS https://getcomposer.org/installer | php

	npm install -g grunt-cli

} > /dev/null 2>&1
