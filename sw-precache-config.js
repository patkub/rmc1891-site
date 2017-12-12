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
    'build/es5-bundled/node_modules/**/*',
    'build/es5-bundled/bower_components/**/*',
    'build/es5-bundled/fonts/**/*',
    'build/es5-bundled/images/**/*',
    'build/es5-bundled/php/**/*',
    'build/es5-bundled/src/**/*',
    'build/es5-bundled/index.html',
  ],
  stripPrefix: 'build/es5-bundled/',
  navigateFallback: 'index.html',
};
