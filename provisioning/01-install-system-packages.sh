#!/bin/bash

{
	apt-get update
	apt-get -y install php5-cli curl nginx nodejs nodejs-legacy npm git-core

	cd /vagrant
	curl -sS https://getcomposer.org/installer | php

	npm install -g grunt-cli

} > /dev/null 2>&1
