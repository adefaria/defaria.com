# DeFaria.com
This is my website for defaria.com and also my corporate site clearscm.com. For
the longest time (cira 2001 or so) I had been running my domain from my house.
Initially I had run it on a Windows PC and used [Cygwin](http://cygwin.com) to
run Apache, ftp and email. My email is filtered by spam filters of my own 
creation called [MAPS](http://defaria.com/maps.doc).

Unfortunately this meant 1) I needed a static IP address, which often ISPs
charged significantly more for and 2) whenever I moved (and I 
[move a lot](http://defaria.com/addresses.php)) my website and email would be
down.

Eventually I moved my server to DreamCompute where I implemented it as and
OpenStack instance. I choose the smallest machine type as my website doesn't
get a lot of traffic but I was occasionally running out of memory so I decided
to reimplement it with a larger instance size and also decided to configure it
with Ansible (see playbook below).

Essentially this is just a dump of what I had on my old server, warts and all.
Over time I will clean it up, etc.