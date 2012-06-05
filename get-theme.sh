#!/bin/bash
rm -rf themes 2>/dev/null
mkdir themes/ 2>/dev/null
git clone ssh://changegit@10.199.99.148/home/changegit/repository/themes.default.git themes/default -b 3.6
rm -rf themes/webfactory/.git 2>/dev/null
git clone ssh://changegit@10.199.99.148/home/changegit/repository/themes.developer.git themes/developer -b 3.6
rm -rf themes/developer/.git 2>/dev/null