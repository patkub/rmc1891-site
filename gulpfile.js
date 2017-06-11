var gulp = require('gulp');
var rename = require("gulp-rename");
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');

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

gulp.task('css', ['sass', 'clean-css']);
gulp.task('default', ['css']);

// "css/rmc-theme.min.css",