#!/bin/bash

if [ ! -d themes/webfactory ]; then
	echo "Missing themes/webfactory directory. Please run get-theme.sh"
	exit 1
fi

if [ ! -d repository ]; then
	echo "Missing repository directory. Please run gen-repository.sh"
	exit 1
fi

rm -rf build
mkdir build

cp pack/change.properties change.properties

cp pack/cmscore.change.xml change.xml
tar --exclude .git -czf build/cmscore-downloader-3.0.4.tgz change.properties change.xml profile config install themes index.php
mkdir build/cmscore-downloader-3.0.4
cd build/cmscore-downloader-3.0.4
tar -xzf ../cmscore-downloader-3.0.4.tgz
zip -r ../cmscore-downloader-3.0.4.zip change.properties change.xml profile config install themes index.php
cd ../..
rm -rf build/cmscore-downloader-3.0.4

tar --exclude .git -czf build/cmscore-3.0.4.tgz change.properties change.xml profile config install themes index.php repository
mkdir build/cmscore-3.0.4
cd build/cmscore-3.0.4
tar -xzf ../cmscore-3.0.4.tgz
zip -r ../cmscore-3.0.4.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/cmscore-3.0.4


cp pack/ecommercecore.change.xml change.xml
tar --exclude .git -czf build/ecommercecore-downloader-3.0.4.tgz change.properties change.xml profile config install themes index.php
mkdir build/ecommercecore-downloader-3.0.4
cd build/ecommercecore-downloader-3.0.4
tar -xzf ../ecommercecore-downloader-3.0.4.tgz
zip -r ../ecommercecore-downloader-3.0.4.zip change.properties change.xml profile config install themes index.php
cd ../..
rm -rf build/ecommercecore-downloader-3.0.4

tar --exclude .git -czf build/ecommercecore-3.0.4.tgz change.properties change.xml profile config install themes index.php repository
mkdir build/ecommercecore-3.0.4
cd build/ecommercecore-3.0.4
tar -xzf ../ecommercecore-3.0.4.tgz
zip -r ../ecommercecore-3.0.4.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/ecommercecore-3.0.4


cp pack/cmsecomos.change.xml change.xml
tar --exclude .git -czf build/cmsecomos-downloader-3.0.4.tgz change.properties change.xml profile config install themes index.php
mkdir build/cmsecomos-downloader-3.0.4
cd build/cmsecomos-downloader-3.0.4
tar -xzf ../cmsecomos-downloader-3.0.4.tgz
zip -r ../cmsecomos-downloader-3.0.4.zip change.properties change.xml profile config install themes index.php
cd ../..
rm -rf build/cmsecomos-downloader-3.0.4

tar --exclude .git -czf build/cmsecomos-3.0.4.tgz change.properties change.xml profile config install themes index.php repository
mkdir build/cmsecomos-3.0.4
cd build/cmsecomos-3.0.4
tar -xzf ../cmsecomos-3.0.4.tgz
zip -r ../cmsecomos-3.0.4.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/cmsecomos-3.0.4
