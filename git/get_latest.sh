#!/bin/sh
chmod 777 -R cbps-db
rm -rf cbps-db


git clone https://github.com/Team-CBPS/cbps-db.git

cd cbps-db
git pull
cd ..