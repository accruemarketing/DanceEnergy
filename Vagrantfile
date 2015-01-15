# -*- mode: ruby -*-
# vi: set ft=ruby :
# Vagrantfile API/syntax version.
VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    config.vm.box = "centos65"
    config.vm.box_url = "http://devopera.com/node/63/download/centos6/doco6-lamp-vagrant.box"
    config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.synced_folder ".", "/var/www/html"
    config.vm.provision "shell", path: "provisioning/bootstrap.sh"
    config.vm.provider "virtualbox" do |v|
      v.memory = 4096
      v.cpus = 1
    end
end