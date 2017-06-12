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

// Compile Stylesheets
gulp.task('css', ['sass', 'clean-css']);

// Compile & Inline CSS
gulp.task('default', ['sass', 'clean-css', 'inline-css']);
