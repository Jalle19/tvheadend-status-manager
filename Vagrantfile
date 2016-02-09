# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/trusty64"

  # networking
  config.vm.network "private_network", ip: "192.168.47.47"

  # memory
  config.vm.provider "virtualbox" do |vb|
    vb.memory = "512"
  end

  # provisioning
  config.vm.provision "shell", path: "provisioning/01-configure-system.sh",
    name: "01 - Configure system"
  config.vm.provision "shell", path: "provisioning/02-install-system-packages.sh",
    name: "02 - Install and update system packages"
  config.vm.provision "shell", path: "provisioning/03-install-dependencies.sh",
    name: "03 - Install dependencies", privileged: false
  config.vm.provision "shell", path: "provisioning/04-configure-nginx.sh",
    name: "04 - Configure nginx"
end
