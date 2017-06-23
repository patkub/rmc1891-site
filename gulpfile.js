/**
 * RMC gulpfile
 *
 * @license
 * Copyright (c) 2017 Rogers Manufacturing Company. All rights reserved.
 */

// include gulp & tools
var gulp = require('gulp');
var rename = require('gulp-rename');
var replace = require('gulp-replace');
var fs = require('fs');
var sass = require('gulp-sass');
var purifyCSS = require('gulp-purifycss');
var cleanCSS = require('gulp-clean-css');
var webp = require('gulp-webp');

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
        .pipe(cleanCSS({
			level: 2,
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('css/'));
});

// WebP
gulp.task('webp', function() {
	return gulp.src([
			'images/**/*',
			'!images/manifest/**/*',
			'!images/favicon.ico',
			'!images/**/*.webp',
		])
		.pipe(webp())
		.pipe(gulp.dest('images/'));
});

// Copy fonts
gulp.task('fonts', function() {
    return gulp.src(['node_modules/components-font-awesome/fonts/*.*'])
        .pipe(gulp.dest('build/es5-bundled/fonts/'))
});

// Copy fonts locally
gulp.task('fonts:local', function() {
    return gulp.src(['node_modules/components-font-awesome/fonts/*.*'])
        .pipe(gulp.dest('fonts/'))
});

// Inline CSS
gulp.task('inline', function() {
    return gulp.src('build/es5-bundled/index.html')
        .pipe(replace('<link rel="stylesheet" href="css/rmc-theme.min.css">', function(s) {
			var style = fs.readFileSync('css/rmc-theme.min.css', 'utf8');
			return '<style>' + style + '</style>';
        }))
        .pipe(gulp.dest('build/es5-bundled/'));
});

// Compile Stylesheets
gulp.task('css', ['sass', 'clean-css']);

// Before polymer build
gulp.task('build:before', ['sass', 'clean-css', 'webp']);

// After polymer build
gulp.task('build:after', ['inline', 'fonts']);

// Serve local
gulp.task('serve:local', ['build:before', 'fonts:local']);
