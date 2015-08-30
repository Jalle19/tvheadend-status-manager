#!/bin/bash

{
	apt-get update
	apt-get -y install php5-cli curl

	cd /vagrant
	curl -sS https://getcomposer.org/installer | php

} > /dev/null 2>&1
