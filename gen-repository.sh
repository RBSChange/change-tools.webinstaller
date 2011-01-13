#!/bin/bash

cp pack/cmsecomos.change.xml change.xml

echo "
# Change remote repositories
REMOTE_REPOSITORIES=http://osrepo.rbschange.fr

# Local repository
# This is a repository stored in the current user's home
LOCAL_REPOSITORY=$PWD/repository

# PEAR installation. It is recommanded to manage a pear repository dedicated to your Change installations
PEAR_INCLUDE_PATH=$PWD/pear

# By default, WWW_GROUP is setted to 'www-data'.
# This value is ok for debian and ubuntu distributions for instance
WWW_GROUP=" > change.properties

echo "*** Running change.php, please wait until command listing ***"
change.php
