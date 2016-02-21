#!/bin/bash

{
	cd /vagrant
	php composer.phar install

	cd /vagrant/src/client
	npm install

	grunt less
	grunt concat

}  > /dev/null 2>&1
