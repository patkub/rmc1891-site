/**
 * RMC gulpfile
 *
 * @license
 * Copyright (c) 2017 Rogers Manufacturing Company. All rights reserved.
 */

'use strict';

// include gulp & tools
import gulp from 'gulp';
import del from 'del';
import rename from 'gulp-rename';
import replace from 'gulp-replace';
import header from 'gulp-header';
import fs from 'fs';
import path from 'path';
import sass from 'gulp-sass';
import purifyCSS from 'gulp-purifycss';
import cleanCSS from 'gulp-clean-css';
import stripCSSComments from 'gulp-strip-css-comments';
import styleLint from 'gulp-stylelint';
import swPrecache from 'sw-precache';
import sftp from 'gulp-sftp';
import minimist from 'minimist';
import browserSync from 'browser-sync';
import historyFallback from 'connect-history-api-fallback';
import pkg from './package.json';
import poly from './polymer.json';

// build path based on build name in polymer.json
const BUILD_ROOT = 'build/';
const BUILD_PATH = BUILD_ROOT + poly.builds[0].name + '/';

const banner = ['<!--',
  '<%= pkg.name %> - <%= pkg.description %>',
  '@version v<%= pkg.version %>',
  '@license <%= pkg.license %>',
  '@copyright 2017 The Rogers Manufacturing Company',
  '@link <%= pkg.homepage %>',
  '@github <%= pkg.repository %>',
  '-->',
  ''].join('\n');

const reload = browserSync.reload;

/**
 * Defines the list of resources to watch for changes.
 */
function watch() {
  gulp.watch(['app/scss/**/*.scss'], ['css', reload]);
  gulp.watch(['app/images/**/*', 'app/php/**/*', 'app/src/**/*'], reload);
  gulp.watch(['index.html'], reload);
}

// Stylelint
gulp.task('lint:css', function() {
  return gulp.src([
    'docs/**/*.html',
    'app/src/**/*.html',
    'test/**/*.html',
    'index.html',
  ]).pipe(styleLint({
    reporters: [
      {formatter: 'string', console: true},
    ],
  }));
});

// Compile Stylesheets
gulp.task('sass', function() {
  return gulp.src('app/scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('app/css/'));
});

// Minify CSS
gulp.task('clean-css', ['sass'], function() {
  return gulp.src('app/css/rmc-theme.css')
    .pipe(purifyCSS([
      'node_modules/bootstrap/dist/js/bootstrap.min.js',
      'app/src/**/*.html',
      'index.html',
    ]))
    .pipe(stripCSSComments({
      preserve: false,
    }))
    .pipe(cleanCSS({
      level: 2,
    }))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('app/css/'));
});

/**
 * Copy api
 */
gulp.task('copy:api', function() {
  return gulp.src([
    'api/api_lib/**/*',
    'api/api_lib/**/.*',
  ]).pipe(gulp.dest(BUILD_ROOT + 'api/'));
});

/**
 * Copy public api
 */
gulp.task('copy:api:public', function() {
  return gulp.src([
    'api/api_public/**/*',
    'api/api_public/**/.*',
  ]).pipe(gulp.dest(BUILD_PATH + 'api/'));
});

/**
 * Copy vendor
 */
gulp.task('copy:vendor', function() {
  return gulp.src([
    'vendor/**/*',
    'vendor/**/.*',
  ]).pipe(gulp.dest(BUILD_ROOT + 'vendor/'));
});

/**
 * Inline CSS & banner
 */
gulp.task('inline', function() {
  return gulp.src(BUILD_PATH + 'index.html')
    .pipe(replace('<link rel="stylesheet" href="app/css/rmc-theme.min.css">', function(s) {
      let style = fs.readFileSync('app/css/rmc-theme.min.css', 'utf8');
      return '<style>' + style + '</style>';
    }))
    .pipe(header(banner, {pkg: pkg}))
    .pipe(gulp.dest(BUILD_PATH));
});

/**
 * Generate precaching service worker
 */
gulp.task('generate-service-worker', ['inline', 'del:after'], function(callback) {
  swPrecache.write(path.join(BUILD_PATH, 'sw.js'), {
    staticFileGlobs: [BUILD_PATH + '/**/*.{html,css,js,otf,eot,svg,ttf,woff,woff2,png,jpg,ico}'],
    stripPrefix: BUILD_PATH,
  }, callback);
});

/**
 * Copy app indexeddb mirror worker
 */
gulp.task('app-indexeddb', function() {
  return gulp.src([
    'node_modules/@npm-polymer/app-storage/app-indexeddb-mirror/app-indexeddb-mirror-worker.js',
    'node_modules/@npm-polymer/app-storage/app-indexeddb-mirror/common-worker-scope.js',
  ]).pipe(gulp.dest(BUILD_PATH));
});

/**
 * Delete build directory
 */
gulp.task('del:before', function() {
  return del([
    BUILD_ROOT,
  ]);
});

/**
 * Delete unneccessary build files
 */
gulp.task('del:after', function() {
  return del([
    BUILD_PATH + 'bower_components/',
  ]);
});

/**
 * Watch resources for changes.
 */
gulp.task('watch', function() {
  watch();
});

/**
 * Serves local landing page from "./" directory.
 */
gulp.task('serve:browsersync:local', () => {
  browserSync({
    server: {
      baseDir: '.',
      middleware: [
        historyFallback(),
      ],
    },
    browser: 'chrome',
  });

  watch();
});

/**
 * Serves production landing page from BUILD_PATH directory.
 */
gulp.task('serve:browsersync:build', () => {
  browserSync({
    server: {
      baseDir: BUILD_PATH,
      middleware: [
        historyFallback(),
      ],
    },
    browser: 'chrome',
  });
});

/**
 * Database connection info
 */
gulp.task('deploy:db', function() {
  /* global process */
  let args = minimist(process.argv.slice());
  
  // replace database connection info
  return gulp.src('build/api/db.ini')
    .pipe(replace('{{host}}', args.dbhost))
    .pipe(replace('{{name}}', args.dbname))
    .pipe(replace('{{user}}', args.dbuser))
    .pipe(replace('{{pass}}', args.dbpass))
    .pipe(gulp.dest('build/api/'));
});

/**
 * Deploys files.
 */
gulp.task('deploy:files', function() {
  /* global process */
  let args = minimist(process.argv.slice());
  
  // copy files
  return gulp.src([
    BUILD_ROOT + '**/*',
    BUILD_ROOT + '**/.*',
  ]).pipe(sftp({
    host: args.host,
    user: args.user,
    pass: args.pass,
    remotePath: '/',
  }));
});

// Compile Stylesheets
gulp.task('css', ['sass', 'clean-css']);

// Before polymer build
gulp.task('build:before', ['del:before', 'css']);

// After polymer build
gulp.task('build:after', ['inline', 'copy:api', 'copy:api:public', 'copy:vendor', 'del:after', 'generate-service-worker', 'app-indexeddb']);

// Serve local
gulp.task('serve:local', ['build:before', 'serve:browsersync:local']);

// Serve production
gulp.task('serve:build', ['serve:browsersync:build']);
