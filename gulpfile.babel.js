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
const FONTS = ['node_modules/font-awesome/fonts/*.*'];

/**
 * Defines the list of resources to watch for changes.
 */
function watch() {
  gulp.watch(['scss/**/*.scss'], ['css', reload]);
  gulp.watch(['php/**/*', 'src/**/*'], reload);
  gulp.watch(['index.html'], reload);
}

// Stylelint
gulp.task('lint:css', function() {
  return gulp.src([
    'docs/**/*.html',
    'src/**/*.html',
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
  return gulp.src('scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('css/'));
});

// Minify CSS
gulp.task('clean-css', ['sass'], function() {
  return gulp.src('css/rmc-theme.css')
    .pipe(purifyCSS([
      'node_modules/bootstrap/dist/js/bootstrap.min.js',
      'src/**/*.html',
      'index.html',
    ]))
    .pipe(stripCSSComments({
      preserve: false,
    }))
    .pipe(cleanCSS({
      level: 2,
    }))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css/'));
});

/**
 * Copy fonts
 */
gulp.task('fonts', function() {
  return gulp.src(FONTS)
    .pipe(gulp.dest('build/es5-bundled/fonts/'));
});

/**
 * Copy fonts locally
 */
gulp.task('fonts:local', function() {
  return gulp.src(FONTS)
    .pipe(gulp.dest('fonts/'));
});

/**
 * Inline CSS & banner
 */
gulp.task('inline', function() {
  return gulp.src('build/es5-bundled/index.html')
    .pipe(replace('<link rel="stylesheet" href="css/rmc-theme.min.css">', function(s) {
      let style = fs.readFileSync('css/rmc-theme.min.css', 'utf8');
      return '<style>' + style + '</style>';
    }))
    .pipe(header(banner, {pkg: pkg}))
    .pipe(gulp.dest('build/es5-bundled/'));
});

/**
 * Generate precaching service worker
 */
gulp.task('generate-service-worker', ['inline', 'fonts', 'del:after'], function(callback) {
  let rootDir = 'build/es5-bundled/';
  
  swPrecache.write(path.join(rootDir, 'sw.js'), {
    staticFileGlobs: [rootDir + '/**/*.{html,css,js,otf,eot,svg,ttf,woff,woff2,png,jpg,ico}'],
    stripPrefix: rootDir,
  }, callback);
});

/**
 * Copy app indexeddb mirror worker
 */
gulp.task('app-indexeddb', function() {
  return gulp.src([
    'node_modules/@npm-polymer/app-storage/app-indexeddb-mirror/app-indexeddb-mirror-worker.js',
    'node_modules/@npm-polymer/app-storage/app-indexeddb-mirror/common-worker-scope.js',
  ]).pipe(gulp.dest('build/es5-bundled/'));
});

/**
 * Delete build directory
 */
gulp.task('del:before', function() {
  return del([
    'build/',
  ]);
});

/**
 * Delete unneccessary build files
 */
gulp.task('del:after', function() {
  return del([
    'build/es5-bundled/bower_components/',
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
 * Serves production landing page from "build/es5-bundled/" directory.
 */
gulp.task('serve:browsersync:build', () => {
  browserSync({
    server: {
      baseDir: 'build/es5-bundled/',
      middleware: [
        historyFallback(),
      ],
    },
    browser: 'chrome',
  });
});

/**
 * MySQL database connection info
 */
gulp.task('deploy:mysql', function() {
  /* global process */
  let args = minimist(process.argv.slice());
  
  // replace private database connection info
  return gulp.src('private/db.ini')
    .pipe(replace('{{host}}', args.dbhost))
    .pipe(replace('{{name}}', args.dbname))
    .pipe(replace('{{user}}', args.dbuser))
    .pipe(replace('{{pass}}', args.dbpass))
    .pipe(gulp.dest('build/es5-bundled/private/'));
});

/**
 * Deploys public files.
 */
gulp.task('deploy:public', function() {
  /* global process */
  let args = minimist(process.argv.slice());
  
  // public site
  return gulp.src([
    'build/es5-bundled/**/*',
    'build/es5-bundled/**/.*',
    '!build/es5-bundled/private/*',
    '!build/es5-bundled/private/.*',
  ]).pipe(sftp({
    host: args.host,
    user: args.user,
    pass: args.pass,
    remotePath: 'public_html/',
  }));
});

/**
 * Deploys private files.
 */
gulp.task('deploy:private', function() {
  /* global process */
  let args = minimist(process.argv.slice());
  
  // private files
  return gulp.src([
    'build/es5-bundled/private/**/*',
    'build/es5-bundled/private/**/.*',
  ])
    .pipe(replace('{{host}}', args.dbhost))
    .pipe(replace('{{name}}', args.dbname))
    .pipe(replace('{{user}}', args.dbuser))
    .pipe(replace('{{pass}}', args.dbpass))
    .pipe(sftp({
      host: args.host,
      user: args.user,
      pass: args.pass,
      remotePath: 'private/',
    }));
});

// Compile Stylesheets
gulp.task('css', ['sass', 'clean-css']);

// Before polymer build
gulp.task('build:before', ['del:before', 'css']);

// After polymer build
gulp.task('build:after', ['inline', 'fonts', 'del:after', 'generate-service-worker', 'app-indexeddb']);

// Serve local
gulp.task('serve:local', ['build:before', 'fonts:local', 'serve:browsersync:local']);

// Serve production
gulp.task('serve:build', ['serve:browsersync:build']);
