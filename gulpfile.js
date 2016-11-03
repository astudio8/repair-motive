var gulp       		= require('gulp');
var util       		= require('gulp-util');
var concat 			= require('gulp-concat');
var uglify     		= require('gulp-uglify');
var source     		= require('vinyl-source-stream');
var stringify  		= require('stringify');
var sass       		= require('gulp-sass');
var minify          = require('gulp-minify');
var cleanCSS        = require('gulp-clean-css');

var rename = require('gulp-rename');

var path = {
    SASS: './www/src/sass/**/*.scss',
    JS: './www/src/js/**/*.js',
    DEST: './www/dist/js'
};

gulp.task('sass', function () {
    return gulp
        // Find all `.scss` files
        .src('www/src/sass/**/*.scss')
        // Run Sass on those files
        .pipe(sass())
        // Write the resulting CSS in the output folder
        //.pipe(gulp.dest('www/dist/css'))
        .pipe(cleanCSS())
        .pipe(rename(function (path) {
            path.basename += '.min';
        }))
        .pipe(gulp.dest('./www/dist/css'));
});

gulp.task('minify-css', function() {
    return gulp.src('styles/*.css')
        .pipe(cleanCSS({debug: true}, function(details) {
        }))
        .pipe(gulp.dest('dist'));
});

/* Compress the JS */
gulp.task('compress-js', function() {

    return gulp.src(path.JS)
        .pipe(concat('app.min.js'))
        .pipe(minify({
            ext:{
                src:'-debug.js',
                min:'.js'
            },
            ignoreFiles: ['-min.js']
        }))
        .pipe(gulp.dest('www/dist/js'))
});

gulp.task('watch', function () {
    "use strict";

    gulp.watch(path.SASS, ['sass']);
    gulp.watch('www/src/js/*.js', ['compress-js']);
});

gulp.task('build', ['sass', 'compress-js']);
gulp.task('default', ['build', 'watch']);