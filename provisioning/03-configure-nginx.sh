#!/bin/bash

{
	cd /vagrant/provisioning

	# disable the default site
	rm /etc/nginx/sites-enabled/default

	# copy and enable our own site
	cp etc/nginx/sites-available/tvheadend-status-manager /etc/nginx/sites-available
	ln -s /etc/nginx/sites-available/tvheadend-status-manager /etc/nginx/sites-enabled

	# restart nginx
	service nginx restart
}  > /dev/null 2>&1
