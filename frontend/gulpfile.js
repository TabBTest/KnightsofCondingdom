'use strict';

var gulp = require('gulp');
var concat = require('gulp-concat');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

var paths = {
  scripts: ['../vendor/bower/bootstrap-material-design/dist/js/*.min.js'],
  css: ['../vendor/bower/bootstrap-material-design/dist/css/ripples.min.css'],
  sass: ['src/sass/*.scss']
};

gulp.task('scripts', function() {
  return gulp.src(paths.scripts)
    .pipe(gulp.dest('build/js'));
});

gulp.task('css', function() {
  return gulp.src(paths.css)
    .pipe(gulp.dest('build/css'));
});

gulp.task('sass', function() {
  return gulp.src(paths.sass)
    .pipe(sourcemaps.init())
    .pipe(sass({
      includePaths: '../vendor/bower/bootstrap-sass/assets/stylesheets'}).on('error', sass.logError))
    .pipe(concat('bootstrap-material-design.min.css'))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest('build/css'));
});

gulp.task('default', ['scripts', 'css', 'sass']);
