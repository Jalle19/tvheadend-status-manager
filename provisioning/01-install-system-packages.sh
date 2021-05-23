#!/bin/bash

{
	add-apt-repository -y ppa:ondrej/php
	
	apt-get update
	apt-get -y install php7.4-cli php7.4-xdebug php7.4-xml php7.4-sqlite3 php7.4-curl php7.4-mbstring curl git-core unzip
	
	# install nodejs from repository
	curl -sL https://deb.nodesource.com/setup_14.x | bash -
	apt-get install -y nodejs

	cd /vagrant
	curl -sS https://getcomposer.org/installer | php

	npm install -g grunt-cli

}
