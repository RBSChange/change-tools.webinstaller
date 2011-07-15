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
tar --exclude .git -czf build/cmscore-downloader-3.5.0.tgz change.properties change.xml profile config install themes index.php repository/framework
mkdir build/cmscore-downloader-3.5.0
cd build/cmscore-downloader-3.5.0
tar -xzf ../cmscore-downloader-3.5.0.tgz
zip -r ../cmscore-downloader-3.5.0.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/cmscore-downloader-3.5.0

tar --exclude .git -czf build/cmscore-3.5.0.tgz change.properties change.xml profile config install themes index.php repository
mkdir build/cmscore-3.5.0
cd build/cmscore-3.5.0
tar -xzf ../cmscore-3.5.0.tgz
zip -r ../cmscore-3.5.0.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/cmscore-3.5.0


cp pack/ecommercecore.change.xml change.xml
tar --exclude .git -czf build/ecommercecore-downloader-3.5.0.tgz change.properties change.xml profile config install themes index.php repository/framework
mkdir build/ecommercecore-downloader-3.5.0
cd build/ecommercecore-downloader-3.5.0
tar -xzf ../ecommercecore-downloader-3.5.0.tgz
zip -r ../ecommercecore-downloader-3.5.0.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/ecommercecore-downloader-3.5.0

tar --exclude .git -czf build/ecommercecore-3.5.0.tgz change.properties change.xml profile config install themes index.php repository
mkdir build/ecommercecore-3.5.0
cd build/ecommercecore-3.5.0
tar -xzf ../ecommercecore-3.5.0.tgz
zip -r ../ecommercecore-3.5.0.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/ecommercecore-3.5.0
