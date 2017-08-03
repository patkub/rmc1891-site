#!/bin/bash
set -ev
if [ "${CIRCLE_BRANCH}" = "deploy" ]; then
  gulp deploy:db --host "${FTP_HOST}" --user "${FTP_USER}" --pass "${FTP_PASS}" --dbhost "${DB_HOST}" --dbname "${DB_NAME}" --dbuser "${DB_USER}" --dbpass "${DB_PASS}"
	gulp deploy:files --host "${FTP_HOST}" --user "${FTP_USER}" --pass "${FTP_PASS}"
fi
