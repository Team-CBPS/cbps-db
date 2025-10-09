#!/bin/sh
cd cbps-db
git config --global user.name "git-username"
git config --global user.email "git-email"


git checkout -b $1
git commit -a -m $1
git push https://CbpsDBCsvUpdater:PLACEHOLDER@github.com/git-username/git-repo.git $1

cd ..

rm -rf cbps-db
