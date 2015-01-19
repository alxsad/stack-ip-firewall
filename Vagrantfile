VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "intellectsoft/wheezy64"
  config.vm.network "forwarded_port", guest: 8080, host: 8080, auto_correct: true
  config.vm.network "private_network", ip: "192.168.50.9"
  config.vm.synced_folder ".", "/vagrant", id: "vagrant-root", :nfs => true
  config.vm.provision "ansible" do |ansible|
    ansible.playbook = "devops/provision/vagrant.yml"
    ansible.inventory_path = "devops/provision/inventory"
    ansible.sudo = true
    ansible.verbose = false
    ansible.limit = "vagrant"
    ansible.host_key_checking = false
    ansible.raw_ssh_args = ["-o UserKnownHostsFile=/dev/null"]
  end
end
