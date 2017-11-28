# Ansible Playbook for DeFaria.com 
This is an Ansible playbook to set up the defaria.com web site. I choose Ansible
over other configuration management tools like Puppet, Chef of Salt is that 
those tools require installation of a client and require a server. Ansible, on
the other hand, is more of a push technology in that all you need to set up is
ssh with pre-shared keys. So no server is involved. This is handy in that I can
keep this playbook on my laptop and my laptop cannot generally serve as a 
server in that it will probably have a dynamically assigned IP address, etc.

Also this playbook isn't really intended to be used to produce hundreds of
defaria.com servers, rather it serves more as codified version of the 
configuration steps needed to set up a single defaria.com and as a vehicle for
learning Ansible.

## Let's Encrypt

The current package obtained from https://github.com/thefinn93/ansible-letsencrypt
has not been tested on CentOS 7 and I couldn't get it to work. One big problem is
that letsencrypt wants to be able to verify you have control of the domain before
it will issue you a valid certificate. However, generaly I would think that
defaria.com would exist and be up and running while this new instance is created
and configured. So it's only when defaria.com's DNS entry is pointed to the new
IP address that letsencrypt could work. Classic chicken and egg. For now I'm 
simply copying my current letsencrypt keys over. That will allow me to get the
new defaria.com up and running and to check it out to insure everything is working
properly. Then I can do the DNS switch and then I can get new certificates. As
such I have installed getssl, which I hope will help me when I get to that point.

## Provision Server

While I've automated the process of provisioning and OpenStack instance on
DreamCompute, when I'm done I have a new instance with a public IP. I have not
figured out how to automatically plug that into the next step to say to Ansible
to run the playbook against this IP address.

## Three Step Process

I envision a 3 step process here:

* Run ansible-playbook roles/genasys/launch_server.yml to provision the CentOS
  instance. This should take about a minute. When finished log into DreamCompute
  to obtain it's public IP address.

* Run ansible-playbook -i<IP>, --key-file ~/.ssh/dreamcompute.pem site.yml to 
  configure this new instance. This should take about 10 minutes.

* Test new server. When satisfied point defaria.com to new IP address.
