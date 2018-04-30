# Robotic Accounting

This repo contains all of the RA code, along with a Vagrant environment for testing.

## Instructions

* Install Vagrant, along with a hypervisor (virtualbox or libvirt)
* Install ansible 2.4 from http://docs.ansible.com/ansible/latest/intro_installation.html
* `vagrant up`
* Navigate to http://localhost:7080/ or http://localhost:7080/admin/
** Default client login: raclient@example.com / raclient123pass
** Default admin login: raadmin / raadmin123pass

## Copying live database (to commit changes back to SVN - be careful when merging)
`vagrant ssh -c "mysql -ura -prapassword ra -e 'truncate table tbladminlog';
`vagrant ssh -c "mysqldump -ura -prapassword ra --tables DESIREDTABLE" > robotacct_NAMEHERE.sql`

## Sample schema data

db/schema.*.json are for use with mockaroo.com sample data generator

## Doing a copy-only deployment

`vagrant provision --provision-with=shell`

## Running locally

as user:
* apt install git dirmngr iptables-persistent
* git clone git@bitbucket.org:robotaccounting/ra.git
* sudo mv ra /var/

as root:
* add `deb http://ppa.launchpad.net/ansible/ansible/ubuntu trusty main` to /etc/apt/sources.list.d/ansible
* apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 93C4A3FD7BB9C367
* apt-get update
* apt-get install ansible
* iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
* iptables -A INPUT -p tcp --dport 22 -j ACCEPT
* iptables -A INPUT -s 113.21.227.203 -j ACCEPT
* iptables -P INPUT DROP
* iptables -P FORWARD DROP
* iptables-save  > /etc/iptables/rules.v4 
* ip6tables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
* ip6tables -A INPUT -s 2403:2f00:f008:1::/64 -j ACCEPT
* ip6tables -P INPUT DROP
* ip6tables -P FORWARD DROP
* ip6tables-save > /etc/iptables/rules.v6
* edit `/etc/mysql/mariadb.conf.d/50-server.cnf`  and add `plugin-load-add = auth_socket.so` below [mysqld]
* systemctl restart mysql

as user with sudo access:
* cd /var/ra
* echo "localhost ansible_connection=local vagrant=false recaptcha_sitekey="" recaptcha_secretkey="" > inventory
 ( for my.unet.nz: sitekey 6Le4aVQUAAAAAKPWTdB3TX9vo6SXrpx4jt-IbCuC secretkey 6Le4aVQUAAAAAPJ7Lke84YjJzwHQ5TnAoCDQUC5G )
* ansible-playbook -i inventory site.yml 




## Email

maildev is running at http://localhost:7080/maildev/ and can be used to view outbound email (which is not actually sent)
