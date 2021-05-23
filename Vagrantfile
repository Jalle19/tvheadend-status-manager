# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "ubuntu/focal64"

  # networking
  config.vm.network "private_network", ip: "192.168.47.47"

  # memory
  config.vm.provider "virtualbox" do |vb|
    vb.memory = "512"
  end

  # provisioning
  config.vm.provision "shell", path: "provisioning/01-install-system-packages.sh",
    name: "01 - Install and update system packages"
  config.vm.provision "shell", path: "provisioning/02-install-dependencies.sh",
    name: "02 - Install dependencies", privileged: false
  config.vm.provision "shell", path: "provisioning/99-enable-swap.sh",
    name: "99 - Enable swap",
    run: "always"
end
