#!/bin/bash

{
	cd /vagrant
	php composer.phar install

	bower install --allow-root

} > /dev/null 2>&1
