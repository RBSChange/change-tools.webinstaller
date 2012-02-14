#!/bin/bash

if [ ! -d themes/webfactory ]; then
	echo "Missing themes/webfactory directory. Please run get-theme.sh"
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
rm build/cmscore-*.*
zip -r build/cmscore-downloader-3.5.4.zip change.properties change.xml profile config install themes index.php repository/framework
zip -r build/cmscore-3.5.4.zip change.properties change.xml profile config install themes index.php repository

cp pack/ecommercecore.change.xml change.xml
rm build/ecommercecore-*.*
zip -r build/ecommercecore-downloader-3.5.4.zip change.properties change.xml profile config install themes index.php repository/framework
zip -r build/ecommercecore-3.5.4.zip change.properties change.xml profile config install themes index.php repository

cp pack/cmsecomos.change.xml change.xml
rm build/cmsecomos-*.*
zip -r build/cmsecomos-downloader-3.5.4.zip change.properties change.xml profile config install themes index.php repository/framework
zip -r build/cmsecomos-3.5.4.zip change.properties change.xml profile config install themes index.php repository