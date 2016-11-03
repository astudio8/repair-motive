Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.provision "shell", path: "deploy/provision.sh", args: "local"
  config.vm.network :forwarded_port, host: 8888, guest: 80

  config.vm.synced_folder ".", "/websites/repairmotive.com"
end