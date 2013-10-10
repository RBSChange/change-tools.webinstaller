#!/bin/bash

if [ ! -d themes/default ]; then
	echo "Missing themes/default directory. Please run get-theme.sh"
	exit 1
fi

if [ ! -d repository ]; then
	echo "Missing repository directory. Please run gen-repository.sh"
	exit 1
fi

if [ ! -d build ]; then
	mkdir build
fi
cp pack/change.properties change.properties

cp pack/cmscore.change.xml change.xml
rm -rf build/cmscore
mkdir build/cmscore
zip -r build/cmscore/cmscore-3.6.8.zip change.properties change.xml profile config install themes index.php repository

cp pack/ecommercecore.change.xml change.xml
rm -rf build/ecommercecore
mkdir build/ecommercecore
zip -r build/ecommercecore/ecommercecore-3.6.8.zip change.properties change.xml profile config install themes index.php repository

cp pack/cmsecomos.change.xml change.xml
rm -rf build/cmsecomos
mkdir build/cmsecomos
zip -r build/cmsecomos/cmsecomos-3.6.8.zip change.properties change.xml profile config install themes index.php repository
