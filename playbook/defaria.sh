#!/bin/bash
#
# This script is run whenever this VM is booted. We install the bare bones
# tools that we need to set the system. Later a configuration management
# tool such as ansible, puppet, chef, etc will set up the server.

# We should replace this with ansible
# Assumptions: Perl and postfix are already installed
#yum -y install \
#  dovecot \
#  gcc \
#  git \
#  gitweb \
#  httpd \
#  mariadb \
#  mariadb-server \
#  mod_ssl \
#  perl-CPAN \
#  perl-DBD-MySQL \
#  php \
#  php-mysql \
#  readline-devel \
#  vim 

# Apply all updates
yum -y update

# Turn on httpd
#systemctl enable httpd.service
#systemctl start  httpd.service

# Turn on mariadb-server
#systemctl enable mariadb
#systemctl start  mariadb

# Turn on Dovecot (IMAP)
#systemctl enable dovecot
#systemctl start  dovecot

# How to automate setting of mariadb's root password?
# Also how to get databases pre-warmed and database users for MAPS and MT?

# PERL: What Perl modules do I need and how are they installed?
#
# . CPAN (update it)
# . Term::ReadLine::Gnu
# . YAML
# . MIME::Entity

# Get code... We need to move this to github
#cd /opt
#git andrew@defaria.com:/opt/git/clearscm.git .
#git andrew@defaria.com:/opt/git/songbook.git .
#git andrew@defaria.com:/opt/git/media.git .

#cd /var/www
#mv html html.orig
#ln -s /opt/clearscm/defaria.com html

#mkdir /web
#ln -s /var/www/html /web

#ln -s /opt/clearscm/defaria.com/etc/httpd/conf.d/defaria.conf /etc/httpd/conf.d/defaria.conf
#ln -s /opt/clearscm/defaria.com/etc/httpd/users /etc/httpd/users
ln -s /opt/clearscm/defaria.com/etc/letsencrypt /etc/letsencrypt
# Create user and group
groupadd defaria
useradd -g defaria andrew

cd ~andrew
mkdir ~andrew/.ssh
chown andrew.defaria ~andrew/.ssh

cp ~centos/.ssh/authorized_keys ~andrew/.ssh/authorized_keys
chown andrew.defaria ~andrew/.ssh/authorized_keys

ln -s /opt/clearscm/rc .rc
.rc/setup_rc
