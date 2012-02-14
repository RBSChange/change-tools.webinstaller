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


cp pack/partnersdemo.change.xml change.xml
rm build/partnersdemo-3.5.4.*
zip -r build/partnersdemo-3.5.4.zip change.properties change.xml profile config install themes index.php repository