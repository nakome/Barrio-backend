'use strict';

var gulp = require('gulp'),
    watch = require('gulp-watch'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    cssmin = require('gulp-clean-css'),
    imagemin = require('gulp-imagemin'),
    pngquant = require('imagemin-pngquant'),
    rimraf = require('rimraf'),
    rigger = require('rigger');


var path = {

    build: {
        js: 'core/assets/build/js/',
        css: 'core/assets/build/css/',
        img: 'core/assets/build/img/',
        fonts: 'core/assets/build/fonts/'
    },

    src: {
        js: 'core/assets/dev/js/*.js',
        style: 'core/assets/dev/css/*.css',
        img: 'core/assets/dev/img/**/*.*',
        fonts: 'core/assets/dev/fonts/**/*.*'
    },

    watch: {
        js: 'core/assets/dev/js/*.js',
        style: 'core/assets/dev/css/*.css',
        img: 'core/assets/dev/img/**/*.*',
        fonts: 'core/assets/dev/fonts/**/*.*'
    },
    clean: 'core/assets/build'
};


gulp.task('clean', function (cb) {
    rimraf(path.clean, cb);
});

gulp.task('js:build', function () {
    gulp.src(path.src.js)
        .pipe(uglify())
        .pipe(gulp.dest(path.build.js))
        .pipe(reload({stream: true}));
});

gulp.task('style:build', function () {
    gulp.src(path.src.style)
        .pipe(prefixer())
        .pipe(cssmin())
        .pipe(gulp.dest(path.build.css))
        .pipe(reload({stream: true}));
});
gulp.task('image:build', function () {
    gulp.src(path.src.img)
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        .pipe(gulp.dest(path.build.img))
        .pipe(reload({stream: true}));
});
gulp.task('fonts:build', function () {
    gulp.src(path.src.fonts)
        .pipe(gulp.dest(path.build.fonts))
});


gulp.task('build', [
    'js:build',
    'style:build',
    'fonts:build',
    'image:build'
]);


gulp.task('watch', function () {
    watch([path.watch.style], function (event, cb) {
        gulp.start('style:build');
    });
    watch([path.watch.js], function (event, cb) {
        gulp.start('js:build');
    });
    watch([path.watch.img], function (event, cb) {
        gulp.start('image:build');
    });
    watch([path.watch.fonts], function (event, cb) {
        gulp.start('fonts:build');
    });
});


gulp.task('default', ['build', 'watch']);
