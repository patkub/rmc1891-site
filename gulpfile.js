var gulp = require('gulp');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');

var replace = require('gulp-replace');
var fs = require('fs');

gulp.task('sass', function () {
  return gulp.src('scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('css/'));
});

gulp.task('clean-css', function () {
  return gulp.src('css/rmc-theme.css')
    .pipe(cleanCSS())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('css/'));
});

gulp.task('inline-css', function () {
  return gulp.src('build/es5-bundled/index.html')
    .pipe(replace('<link rel="stylesheet" href="css/rmc-theme.min.css">', function(s) {
        var style = fs.readFileSync('css/rmc-theme.min.css', 'utf8');
        return '<style>' + style + '</style>';
    }))
    .pipe(gulp.dest('build/es5-bundled/'));
});

gulp.task('css', ['sass', 'clean-css']);
gulp.task('default', ['css', 'inline-css']);
