#!/bin/bash
set -ev
if [ "${TRAVIS_BRANCH}" = "deploy" ]; then
  gulp deploy:mysql --dbhost "${DB_HOST}" --dbname "${DB_NAME}" --dbuser "${DB_USER}" --dbpass "${DB_PASS}"
	gulp deploy:public --host "${FTP_HOST}" --user "${FTP_USER}" --pass "${FTP_PASS}"
  gulp deploy:private --host "${FTP_HOST}" --user "${FTP_USER}" --pass "${FTP_PASS}"
fi
