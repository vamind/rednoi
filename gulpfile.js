var gulp = require('gulp');
var cleanCSS = require('gulp-clean-css');
var path = require('path');
var colors = require('colors');
var notify = require('gulp-notify');
var uglify = require('gulp-uglify');
var strip = require('gulp-strip-css-comments');
var size = require('gulp-size');
var concat = require('gulp-concat');

var paths = {
    styles: 'web/assets/css',
    js: 'web/assets/js',
    nodejs: 'node_modules',
    build: 'web/www'
};

var jsFiles = [
    paths.nodejs + '/jquery/dist/jquery.min.js',
    paths.nodejs + '/twemoji/2/twemoji.min.js',
    paths.js + '/main.js',
    paths.js + '/twitter.js'
];

var cssFile = paths.styles + '/style.css';


gulp.task('default', ['js', 'css']);

gulp.task('css', ['css_task', 'css_watch']);
gulp.task('css_task', function() {
    return gulp.src(cssFile)
        .pipe(strip())
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest(paths.build + '/css'))
        .pipe(notify('Css minified'))
        .pipe(size({showFiles: true, title: ' Minified CSS: '.bgBlue.black}));
});
gulp.task('css_watch', function () {
    gulp.watch(paths.styles + '/*.css', ['css_task']);
});


gulp.task('js', ['js_uglify', 'js_watch']);
gulp.task('js_uglify', function () {
    return gulp.src(jsFiles)
        .pipe(concat('script.min.js'))
        .pipe(size({showFiles: true, title: ' Concatenated JS '.bgWhite.black}))
        .pipe(uglify())
        .pipe(gulp.dest(paths.build + "/js"))
        .pipe(notify('Js compiled'))
        .pipe(size({showFiles: true, title: ' Minified JS: '.bgYellow.black}));
});
gulp.task('js_watch', function () {
    gulp.watch(jsFiles, ['js_uglify']);
});
