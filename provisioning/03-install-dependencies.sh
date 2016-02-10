#!/bin/bash

{
	cd /vagrant
	php composer.phar install

	npm install
	bower install --allow-root

	grunt less

} > /dev/null 2>&1
