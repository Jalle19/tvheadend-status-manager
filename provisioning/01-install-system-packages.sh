#!/bin/bash

{
	apt-get update
	apt-get -y install php5-cli php5-xdebug curl nginx nodejs nodejs-legacy npm git-core

	cd /vagrant
	curl -sS https://getcomposer.org/installer | php

	npm install -g grunt-cli

} > /dev/null 2>&1
