#!/bin/sh
set -a
. "$(dirname "$0")/../.env"
set +a

cd cbps-db
git config --global user.name "git-username"
git config --global user.email "${GIT_COMMIT_EMAIL}"


git checkout -b $1
git commit -a -m $1
git push https://CbpsDBCsvUpdater:${GITHUB_PUSH_TOKEN}@github.com/Team-CBPS/cbps-db.git $1

cd ..

rm -rf cbps-db
