#!/bin/bash
rm -rf themes 2>/dev/null
mkdir themes/ 2>/dev/null
git clone  ssh://git@rd.devlinux.france.rbs.fr/home/git/repository/themes.default.git themes/default -b 3.6
rm -rf themes/webfactory/.git 2>/dev/null
git clone  ssh://git@rd.devlinux.france.rbs.fr/home/git/repository/themes.developer.git themes/developer -b 3.6
rm -rf themes/developer/.git 2>/dev/null