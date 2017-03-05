var gulp = require('gulp');
// Those were used before gulp-plugins addition
//var sass = require('gulp-sass');
//var sourcemaps = require('gulp-sourcemaps');
//var concat = require('gulp-concat');
//var minifyCSS = require('gulp-minify-css');
//var util = require('gulp-util');
//var gulpif = require('gulp-if');
var plugins = require('gulp-load-plugins')();

var config = {
    assetsDir: 'app/Resources/assets',
    bowerDir: 'vendor/bower_components',
    sassPattern: 'sass/**/*.scss',
    production: !!plugins.util.env.production, // production true
    sourcemaps: !plugins.util.env.production // production false
};

var app = {};

app.addStyle = function( paths, filename ){
    gulp.src(paths)
    .pipe(plugins.plumber()) // Avoid gulp to die when there is an error
    .pipe(plugins.if(config.sourcemaps, plugins.sourcemaps.init())) // Points to original scss file errors in console debugger, not css + gulpif to test env dev or prod
    .pipe(plugins.sass())
    .pipe(plugins.concat(filename))
    .pipe(config.production ? plugins.minifyCss() : plugins.util.noop()) // when running gulp --production, minifies the css. Not minified in dev
    .pipe(plugins.if(config.sourcemaps, plugins.sourcemaps.write('.'))) // Points to original scss file errors in console debugger, not css
    .pipe(gulp.dest('web/css'))
    ;
}

app.addScript = function( paths, filename ){
    gulp.src( paths )
        .pipe(plugins.plumber()) // Avoid gulp to die when there is an error
        .pipe(plugins.if(config.sourcemaps, plugins.sourcemaps.init())) // Points to original scss file errors in console debugger, not css + gulpif to test env dev or prod
        .pipe(plugins.concat( filename ))
        .pipe(config.production ? plugins.uglify() : plugins.util.noop()) // when running gulp --production, minifies the css. Not minified in dev
        .pipe(plugins.if(config.sourcemaps, plugins.sourcemaps.write('.'))) // Points to original scss file errors in console debugger, not css
        .pipe(gulp.dest('web/js'))
    ;
}

gulp.task('styles', function(){
    app.addStyle(
        [
            config.bowerDir + '/bootstrap/dist/css/bootstrap.css',
            config.assetsDir + '/sass/layout.scss',
            config.assetsDir + '/sass/styles.scss'
        ], 
        'main.css'
    );
    app.addStyle(
        [
            config.assetsDir + '/sass/bands.scss'
        ], 
        'bands.css'
    );
});

gulp.task('scripts', function(){
    app.addScript([
        config.bowerDir + '/jquery/dist/jquery.js',
        config.assetsDir + '/js/main.js'
    ], 'site.js');
});

gulp.task('watch', function(){    
    gulp.watch(config.assetsDir + '/' + config.sassPattern, ['styles']);
    gulp.watch(config.assetsDir + '/js/**/*.js', ['scripts']);
});


gulp.task('default', ['styles', 'scripts', 'watch']);

