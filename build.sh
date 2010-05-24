rm -rf build
mkdir build

cp pack/cmscore.change.xml change.xml
tar --exclude .svn -czf build/cmscore-downloader-3.0.2.tgz change.properties change.xml profile config install index.php
mkdir build/cmscore-downloader-3.0.2
cd build/cmscore-downloader-3.0.2
tar -xzf ../cmscore-downloader-3.0.2.tgz
zip -r ../cmscore-downloader-3.0.2.zip change.properties change.xml profile config install index.php
cd ../..
rm -rf build/cmscore-downloader-3.0.2

tar --exclude .svn -czf build/cmscore-3.0.2.tgz change.properties change.xml profile config install index.php repository
mkdir build/cmscore-3.0.2
cd build/cmscore-3.0.2
tar -xzf ../cmscore-3.0.2.tgz
zip -r ../cmscore-3.0.2.zip change.properties change.xml profile config install index.php repository
cd ../..
rm -rf build/cmscore-3.0.2


cp pack/ecommercecore.change.xml change.xml
tar --exclude .svn -czf build/ecommercecore-downloader-3.0.2.tgz change.properties change.xml profile config install index.php
mkdir build/ecommercecore-downloader-3.0.2
cd build/ecommercecore-downloader-3.0.2
tar -xzf ../ecommercecore-downloader-3.0.2.tgz
zip -r ../ecommercecore-downloader-3.0.2.zip change.properties change.xml profile config install index.php
cd ../..
rm -rf build/ecommercecore-downloader-3.0.2

tar --exclude .svn -czf build/ecommercecore-3.0.2.tgz change.properties change.xml profile config install index.php repository
mkdir build/ecommercecore-3.0.2
cd build/ecommercecore-3.0.2
tar -xzf ../ecommercecore-3.0.2.tgz
zip -r ../ecommercecore-3.0.2.zip change.properties change.xml profile config install index.php repository
cd ../..
rm -rf build/ecommercecore-3.0.2


cp pack/cmsecomos.change.xml change.xml
tar --exclude .svn -czf build/cmsecomos-downloader-3.0.2.tgz change.properties change.xml profile config install index.php
mkdir build/cmsecomos-downloader-3.0.2
cd build/cmsecomos-downloader-3.0.2
tar -xzf ../cmsecomos-downloader-3.0.2.tgz
zip -r ../cmsecomos-downloader-3.0.2.zip change.properties change.xml profile config install index.php
cd ../..
rm -rf build/cmsecomos-downloader-3.0.2

tar --exclude .svn -czf build/cmsecomos-3.0.2.tgz change.properties change.xml profile config install index.php repository
mkdir build/cmsecomos-3.0.2
cd build/cmsecomos-3.0.2
tar -xzf ../cmsecomos-3.0.2.tgz
zip -r ../cmsecomos-3.0.2.zip change.properties change.xml profile config install index.php repository
cd ../..
rm -rf build/cmsecomos-3.0.2