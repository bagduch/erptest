

This repo contains all of the XX code, along with a Vagrant environment for testing and Ansible roles/templates for deployment.

## Instructions

* Install Vagrant, along with a hypervisor (virtualbox or libvirt)
* Install ansible 2.4 from http://docs.ansible.com/ansible/latest/intro_installation.html
* `vagrant up` from inside the repo
* Navigate to https://localhost:7081/ or https://localhost:7081/admin/


## Copying live database (to commit changes back to SVN - be careful when merging)



## Sample schema data

`db/schema.*.json` - for use with mockaroo.com sample data generator

## Doing a copy-only deployment

`vagrant provision --provision-with=shell`

## Running locally

For example, as currently deployed to my.unet.nz staging server.

as `root`:


```
Then populate the `ra` database manually.

## Email

maildev is running at http://localhost:7080/maildev/ and can be used to view outbound email (which is not actually sent)

## phpMyAdmin

TODO: Document
