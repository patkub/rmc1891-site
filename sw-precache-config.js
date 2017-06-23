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
	'/node_modules/bootstrap/dist/js/bootstrap.min.js',
	'/node_modules/jquery/dist/jquery.slim.min.{js, map}',
	'/node_modules/tether/dist/js/tether.min.js',
    '/node_modules/@npm-polymer/webcomponentsjs/*.{js, js.map}',
	'/node_modules/components-font-awesome/fonts/*.*',
	'/images/**/*',
	'/js/**/*',
	'/index.html',
	'/mail.php',
	'/manifest.json',
  ],
  navigateFallback: 'index.html',
};
