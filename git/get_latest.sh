#!/bin/sh
chmod 777 -R cbps-db
rm -rf cbps-db


git clone https://github.com/git-username/git-repo.git

cd cbps-db
git pull
cd ..