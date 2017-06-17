/*!
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
var cleanCSS = require('gulp-clean-css');
var webp = require('gulp-webp');

// Compile Stylesheets
gulp.task('sass', function () {
  return gulp.src('scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('css/'));
});

// Minify CSS
gulp.task('clean-css', ['sass'], function () {
  return gulp.src('css/rmc-theme.css')
    .pipe(cleanCSS({
        level: 2
    }))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css/'));
});

// Inline CSS
gulp.task('inline-css', ['clean-css'], function () {
  return gulp.src('build/es5-bundled/index.html')
    .pipe(replace('<link rel="stylesheet" href="css/rmc-theme.min.css">', function(s) {
        var style = fs.readFileSync('css/rmc-theme.min.css', 'utf8');
        return '<style>' + style + '</style>';
    }))
    .pipe(gulp.dest('build/es5-bundled/'));
});

// Inline ES5 Adapter
gulp.task('inline-es5-adapter', function () {
  return gulp.src('build/es5-bundled/index.html')
    .pipe(replace('<script type="text/javascript" src="bower_components/webcomponentsjs/custom-elements-es5-adapter.js"></script>', function(s) {
        var es5adapter = fs.readFileSync('bower_components/webcomponentsjs/custom-elements-es5-adapter.js', 'utf8');
        return '<script>' + es5adapter + '</script>';
    }))
    .pipe(gulp.dest('build/es5-bundled/'));
});

// WebP
gulp.task('webp', function () {
    return gulp.src('images/**/*')
        .pipe(webp())
        .pipe(gulp.dest('build/es5-bundled/images/'));
});

// Compile Stylesheets
gulp.task('css', ['sass', 'clean-css']);

// Compile & Inline
gulp.task('default', ['sass', 'clean-css', 'inline-css', 'inline-es5-adapter']);
