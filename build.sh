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
gnutar --exclude .git -czf build/cmscore-downloader-3.5.1.tgz change.properties change.xml profile config install themes index.php repository/framework
mkdir build/cmscore-downloader-3.5.1
cd build/cmscore-downloader-3.5.1
gnutar -xzf ../cmscore-downloader-3.5.1.tgz
zip -r ../cmscore-downloader-3.5.1.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/cmscore-downloader-3.5.1

gnutar --exclude .git -czf build/cmscore-3.5.1.tgz change.properties change.xml profile config install themes index.php repository
mkdir build/cmscore-3.5.1
cd build/cmscore-3.5.1
gnutar -xzf ../cmscore-3.5.1.tgz
zip -r ../cmscore-3.5.1.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/cmscore-3.5.1


cp pack/ecommercecore.change.xml change.xml
gnutar --exclude .git -czf build/ecommercecore-downloader-3.5.1.tgz change.properties change.xml profile config install themes index.php repository/framework
mkdir build/ecommercecore-downloader-3.5.1
cd build/ecommercecore-downloader-3.5.1
gnutar -xzf ../ecommercecore-downloader-3.5.1.tgz
zip -r ../ecommercecore-downloader-3.5.1.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/ecommercecore-downloader-3.5.1

gnutar --exclude .git -czf build/ecommercecore-3.5.1.tgz change.properties change.xml profile config install themes index.php repository
mkdir build/ecommercecore-3.5.1
cd build/ecommercecore-3.5.1
gnutar -xzf ../ecommercecore-3.5.1.tgz
zip -r ../ecommercecore-3.5.1.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/ecommercecore-3.5.1

cp pack/cmsecomos.change.xml change.xml
gnutar --exclude .git -czf build/cmsecomos-downloader-3.5.1.tgz change.properties change.xml profile config install themes index.php repository/framework
mkdir build/cmsecomos-downloader-3.5.1
cd build/cmsecomos-downloader-3.5.1
gnutar -xzf ../cmsecomos-downloader-3.5.1.tgz
zip -r ../cmsecomos-downloader-3.5.1.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/cmsecomos-downloader-3.5.1

gnutar --exclude .git -czf build/cmsecomos-3.5.1.tgz change.properties change.xml profile config install themes index.php repository
mkdir build/cmsecomos-3.5.1
cd build/cmsecomos-3.5.1
gnutar -xzf ../cmsecomos-3.5.1.tgz
zip -r ../cmsecomos-3.5.1.zip change.properties change.xml profile config install themes index.php repository
cd ../..
rm -rf build/cmsecomos-3.5.1
