/**
 * RMC gulpfile
 *
 * @license
 * Copyright (c) 2017 Rogers Manufacturing Company. All rights reserved.
 */

'use strict';

// include gulp & tools
import gulp from 'gulp';
import rename from 'gulp-rename';
import replace from 'gulp-replace';
import header from 'gulp-header';
import fs from 'fs';
import sass from 'gulp-sass';
import purifyCSS from 'gulp-purifycss';
import cleanCSS from 'gulp-clean-css';
import stripCSSComments from 'gulp-strip-css-comments';
import webp from 'gulp-webp';
import browserSync from 'browser-sync';
import pkg from './package.json';

const banner = ['<!--',
  '<%= pkg.name %> - <%= pkg.description %>',
  '@version v<%= pkg.version %>',
  '@license <%= pkg.license %>',
  '@copyright 2017 The Rogers Manufacturing Company',
  '@link <%= pkg.homepage %>',
  '-->',
  ''].join('\n');

const reload = browserSync.reload;
const FONTS = ['node_modules/components-font-awesome/fonts/*.*'];
const IMAGES = [
    'images/**/*',
    '!images/manifest/**/*',
    '!images/favicon.ico',
    '!images/**/*.webp',
];

/**
 * Defines the list of resources to watch for changes.
 */
function watch() {
    gulp.watch(['scss/**/*.scss'], ['css', reload]);
    gulp.watch(IMAGES, ['webp', reload]);
    gulp.watch(['node_modules/components-font-awesome/fonts/*.*'], ['fonts:local', reload]);
    gulp.watch(['src/**/*'], reload);
    gulp.watch(['index.html'], reload);
}

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

// WebP
gulp.task('webp', function() {
	return gulp.src(IMAGES)
		.pipe(webp())
		.pipe(gulp.dest('images/'));
});

// Copy fonts
gulp.task('fonts', function() {
    return gulp.src(FONTS)
        .pipe(gulp.dest('build/es5-bundled/fonts/'));
});

// Copy fonts locally
gulp.task('fonts:local', function() {
    return gulp.src(FONTS)
        .pipe(gulp.dest('fonts/'));
});

// Inline CSS & banner
gulp.task('inline', function() {
    return gulp.src('build/es5-bundled/index.html')
        .pipe(replace('<link rel="stylesheet" href="css/rmc-theme.min.css">', function(s) {
			var style = fs.readFileSync('css/rmc-theme.min.css', 'utf8');
			return '<style>' + style + '</style>';
        }))
        .pipe(header(banner, {pkg: pkg}))
        .pipe(gulp.dest('build/es5-bundled/'));
});

// Watch resources for changes.
gulp.task('watch', function() {
    watch();
});

/**
 * Serves local landing page from "./" directory.
 */
gulp.task('serve:browsersync:local', () => {
  browserSync({
    notify: false,
    server: '.',
    browser: 'chrome',
  });

  watch();
});

/**
 * Serves production landing page from "build/es5-bundled/" directory.
 */
gulp.task('serve:browsersync:build', () => {
  browserSync({
    notify: false,
    server: {
      baseDir: ['build/es5-bundled/'],
    },
    browser: 'chrome',
  });

  watch();
});

// Compile Stylesheets
gulp.task('css', ['sass', 'clean-css']);

// Before polymer build
gulp.task('build:before', ['sass', 'clean-css', 'webp']);

// After polymer build
gulp.task('build:after', ['inline', 'fonts']);

// Serve local
gulp.task('serve:local', ['build:before', 'fonts:local', 'serve:browsersync:local']);
gulp.task('serve:build', ['serve:browsersync:build']);
