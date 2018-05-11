#!/usr/bin/env bash

commit=$1
if [ -z ${commit} ]; then
    commit=$(git tag --sort=-creatordate | head -1)
    if [ -z ${commit} ]; then
        commit="master";
    fi
fi

# Remove old release
rm -rf FroshTemplateMail FroshTemplateMail-*.zip

# Build new release
mkdir -p FroshTemplateMail
git archive ${commit} | tar -x -C FroshTemplateMail
composer install --no-dev -n -o -d FroshTemplateMail
( find ./FroshTemplateMail -type d -name ".git" && find ./FroshTemplateMail -name ".gitignore" && find ./FroshTemplateMail -name ".gitmodules" ) | xargs rm -r
zip -r FroshTemplateMail-${commit}.zip FroshTemplateMail