#!/bin/bash
rm -rf themes 2>/dev/null
mkdir themes/ 2>/dev/null
git clone git@github.com:RBSChange/themes.default.git themes/default -b 3.6
rm -rf themes/webfactory/.git 2>/dev/null
git clone git@github.com:RBSChange/themes.developer.git themes/developer -b 3.6
rm -rf themes/developer/.git 2>/dev/null
