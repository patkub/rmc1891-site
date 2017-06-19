/**
 * @license
 * Copyright (c) 2016 The Polymer Project Authors. All rights reserved.
 * This code may only be used under the BSD style license found at http://polymer.github.io/LICENSE.txt
 * The complete set of authors may be found at http://polymer.github.io/AUTHORS.txt
 * The complete set of contributors may be found at http://polymer.github.io/CONTRIBUTORS.txt
 * Code distributed by Google as part of the polymer project is also
 * subject to an additional IP rights grant found at http://polymer.github.io/PATENTS.txt
 */

/* eslint-env node */

module.exports = {
  staticFileGlobs: [
	'/bower_components/webcomponentsjs/*.js',
	'/bower_components/bootstrap/dist/js/bootstrap.min.js',
	'/bower_components/jquery/dist/jquery.slim.min.js',
	'/bower_components/tether/dist/js/tether.min.js',
	'/images/**/*',
	'/js/**/*',
	'/.htaccess',
	'/index.html',
	'/mail.php',
	'/manifest.json'
  ],
  navigateFallback: 'index.html',
};
