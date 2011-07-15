#!/bin/bash
rm -rf themes 2>/dev/null
mkdir themes/ 2>/dev/null
git clone  ssh://git@rd.devlinux.france.rbs.fr/home/git/repository/themes.webfactory.git themes/webfactory -b 3.5
rm -rf themes/webfactory/.git 2>/dev/null
git clone  ssh://git@rd.devlinux.france.rbs.fr/home/git/repository/themes.developer.git themes/developer -b 3.5
rm -rf themes/developer/.git 2>/dev/null