#!/bin/bash

{
	cd /vagrant
	php composer.phar install

} > /dev/null 2>&1
