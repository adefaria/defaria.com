# Ansible Playbook for DeFaria.com 
This is an Ansible playbook to set up the defaria.com web site. I choose Ansible
over other configuration management tools like Puppet, Chef of Salt is that 
those tools require installation of a client and require a server. Ansible, on
the other hand, is more of a push technology in that all you need to set up is
ssh with pre-shared keys. So no server is involved. This is handy in that I can
keep this playbook on my laptop and my laptop cannot generally server as a 
server in that it will probably have a dynamically assigned IP address, etc.

Also this playbook isn't really intended to be used to produce hundreds of
defaria.com servers, rather it serves more as codified version of the 
configuration steps needed to set up a single defaria.com and as a vehicle for
learning ansible.

This playbook is designed to be run from root. This way we should just need to
establish a secure, pre-shared key ssh login from root@laptop ->
root@newdefaria.com. Then Ansible can do the rest. Look at how we can best use
this like setting up ssh keys for the andrew user.

## Let's Encrypt:

The current package obtained from https://github.com/thefinn93/ansible-letsencrypt
has not been tested on CentOS 7 so I need to make some changes. So far I changed:

* Change include -> import_tasks as newer version of Ansible complains that
  include is deprecated.
