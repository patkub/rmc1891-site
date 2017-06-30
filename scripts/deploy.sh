#!/bin/bash
set -ev
if [ "${TRAVIS_BRANCH}" = "deploy" ]; then
	gulp deploy --host "${FTP_HOST}" --user "${FTP_USER}" --pass "${FTP_PASS}"
fi
