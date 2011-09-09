#!/bin/bash

rm -rf buildproject
mkdir buildproject

cp pack/cmsecomos.change.xml buildproject/change.xml

REMOTE_REPO=http://devrepo.rbschange.fr
VERSION=3.5.1

echo "
# Change remote repositories
REMOTE_REPOSITORIES=$REMOTE_REPO

# Local repository
# This is a repository stored in the current user's home
LOCAL_REPOSITORY=$PWD/repository

# PEAR installation. It is recommanded to manage a pear repository dedicated to your Change installations
PEAR_INCLUDE_PATH=$PWD/buildproject/pear

# By default, WWW_GROUP is setted to 'www-data'.
# This value is ok for debian and ubuntu distributions for instance
WWW_GROUP=" > buildproject/change.properties

cd buildproject

echo "build" > profile

echo "*** Download framework-$VERSION from $REMOTE_REPO ***"
curl $REMOTE_REPO/framework/framework-$VERSION.zip -o framework-$VERSION.zip 2>/dev/null

if [ $? -ne 0 ]; then
  echo "Error downloading framework-$VERSION.zip"
  exit 1
fi

unzip -o "framework-$VERSION.zip" >/dev/null 2>&1
ln -s framework-$VERSION framework

echo "*** Running change.php update-dependencies, please wait until command listing ***"
php framework/bin/change.php update-dependencies
