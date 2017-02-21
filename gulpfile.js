var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');
var minifyCSS = require('gulp-minify-css');
var util = require('gulp-util');
var gulpif = require('gulp-if');

var config = {
    assetsDir: 'app/Resources/assets',
    sassPattern: 'sass/**/*.scss',
    production: !!util.env.production, // production true
    sourcemaps: !util.env.production // production false
};

gulp.task('sass', function(){
    gulp.src(config.assetsDir + '/' + config.sassPattern)
        .pipe(gulpif(config.sourcemaps, sourcemaps.init())) // Points to original scss file errors in console debugger, not css + gulpif to test env dev or prod
        .pipe(sass())
        .pipe(concat('main.css'))
        .pipe(config.production ? minifyCSS() : util.noop()) // when running gulp --production, minifies the css. Not minified in dev
        .pipe(gulpif(config.sourcemaps, sourcemaps.write('.'))) // Points to original scss file errors in console debugger, not css
        .pipe(gulp.dest('web/css'))
    ;
});

gulp.task('watch', function(){
    gulp.watch(config.assetsDir + '/' + config.sassPattern, ['sass']);
});

gulp.task('default', ['sass', 'watch']);

