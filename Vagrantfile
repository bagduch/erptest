# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|
  config.vm.box = "debian/stretch64"

  config.vm.provider "virtualbox" do |v, override|
    override.vm.box = "sagepe/stretch"
    override.vm.box_version = "1.0.0"
    v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
  end

  config.vm.provider "libvirt" do |v, override|
    v.memory = 1024 # composer + mariadb at the same time was OOMing
  end

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  # config.vm.network "forwarded_port", guest: 80, host: 8080

  # Create a private network, which allows host-only access to the machine
  # using a specific IP.
  #config.vm.network "private_network", ip: "192.168.33.10"

  # Create a public network, which generally matched to bridged network.
  # Bridged networks make the machine appear as another physical device on
  # your network.
  # config.vm.network "public_network"
  config.vm.network "forwarded_port", guest: 80, host: 7080
  config.vm.network "forwarded_port", guest: 443, host: 7081


	# Need to use https://github.com/ZoranPavlovic/vagrant-guest_ansible,
	# which has been patched for the pip HTTP->HTTPS switch

  if Vagrant::Util::Platform.windows?
    config.vm.provision "shell" do |shell|
      shell.inline = "echo deb http://ppa.launchpad.net/ansible/ansible/ubuntu trusty main >> /etc/apt/sources.list.d/ansible.list && apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 93C4A3FD7BB9C367 && apt-get update && apt-get install ansible"
    end
    config.vm.provision "guest_ansible" do |ansible|
      ansible.playbook = "deploy.yml"
      ansible.verbose = "vvvv"
      ansible.extra_vars = {
        vagrant: true,
        # recaptcha keys for localhost from guy.hd123@gmail.com account
        recaptcha_sitekey: "6LcrTEwUAAAAALQmVxWRt81yvjAQ_H_ZLy2E22nE",
        recaptcha_secretkey: "6LcrTEwUAAAAALV5AtmyeLeSHYhWfjnAa16ovDmz",
	      repo_basepath: "/vagrant/", # path this file is in
        ra_hostname: "roboticaccounting.localhost"
      }
    end
	else
    config.vm.provision "ansible" do |ansible|
      ansible.playbook = "site.yml"
      ansible.verbose = "vvvv"
      ansible.extra_vars = {
        vagrant: true,
        # recaptcha keys for localhost from guy.hd123@gmail.com account
        recaptcha_sitekey: "6LcrTEwUAAAAALQmVxWRt81yvjAQ_H_ZLy2E22nE",
        recaptcha_secretkey: "6LcrTEwUAAAAALV5AtmyeLeSHYhWfjnAa16ovDmz",
	      repo_basepath: "/vagrant/", # path this file is in
        ra_hostname: "roboticaccounting.localhost"
      }
    end
	end

  config.vm.provision "shell" do |shell|
    shell.inline =  "rsync -av --delete --exclude=templates_c --exclude=configuration.php /vagrant/html/ /var/www/html/"
  end

end
# vim: ai ts=2 sts=2 et sw=2 ft=ruby
