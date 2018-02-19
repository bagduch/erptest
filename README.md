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


## Email

maildev is running at http://localhost:7080/maildev/ and can be used to view outbound email (which is not actually sent)
