var gulp = require('gulp');
// Those were used before gulp-plugins addition
//var sass = require('gulp-sass');
//var sourcemaps = require('gulp-sourcemaps');
//var concat = require('gulp-concat');
//var minifyCSS = require('gulp-minify-css');
//var util = require('gulp-util');
//var gulpif = require('gulp-if');
var plugins = require('gulp-load-plugins')();
var del = require('del');
var Q = require('q'); // Queuing tool

var config = {    
    assetsDir: 'app/Resources/assets',
    bowerDir: 'vendor/bower_components',
    sassPattern: 'sass/**/*.scss',
    production: !!plugins.util.env.production, // production true
    sourcemaps: !plugins.util.env.production, // production false
    revManifestPath: 'app/Resources/assets/rev-manifest.json'
};

var app = {};

app.addStyle = function( paths, filename ){
    return gulp.src(paths)
        // Plumber avoids gulp to die when there is an error
        .pipe(plugins.plumber())
        .pipe(plugins.if(config.sourcemaps, plugins.sourcemaps.init())) // Points to original scss file errors in console debugger, not css + gulpif to test env dev or prod
        .pipe(plugins.sass())
        .pipe(plugins.concat('css/' + filename))
        .pipe(config.production ? plugins.minifyCss() : plugins.util.noop()) // when running gulp --production, minifies the css. Not minified in dev
        .pipe(plugins.rev())
        .pipe(plugins.if(config.sourcemaps, plugins.sourcemaps.write('.'))) // Points to original scss file errors in console debugger, not css
        .pipe(gulp.dest('web'))
        .pipe(plugins.rev.manifest(config.revManifestPath, { merge: true }))
        .pipe(gulp.dest('.'))
    ;
};

app.addScript = function( paths, filename ){
    return gulp.src( paths )        
        .pipe(plugins.plumber())
        .pipe(plugins.if(config.sourcemaps, plugins.sourcemaps.init())) // Points to original scss file errors in console debugger, not css + gulpif to test env dev or prod
        .pipe(plugins.concat( 'js/' + filename ))
        .pipe(config.production ? plugins.uglify() : plugins.util.noop()) // when running gulp --production, minifies the css. Not minified in dev
        .pipe(plugins.rev())
        .pipe(plugins.if(config.sourcemaps, plugins.sourcemaps.write('.'))) // Points to original scss file errors in console debugger, not css
        .pipe(gulp.dest('web'))
        .pipe(plugins.rev.manifest(config.revManifestPath, { merge: true }))
        .pipe(gulp.dest('.'))
    ;
};

app.copy = function( srcFiles, outputDir ){
    return gulp.src(srcFiles)
        .pipe(gulp.dest(outputDir))
    ;
};

gulp.task('styles', function(){
    var pipeline = new Pipeline();
    
    pipeline.add(
        [
            config.bowerDir + '/bootstrap/dist/css/bootstrap.css',
            config.bowerDir + '/font-awesome/css/font-awesome.css',
            config.assetsDir + '/sass/layout.scss',
            config.assetsDir + '/sass/styles.scss'
        ], 
        'main.css'
    );
    
    pipeline.add(
        [
            config.assetsDir + '/sass/bands.scss'
        ], 
        'bands.css'
    );
    
    return pipeline.run(app.addStyle);
});

gulp.task('scripts', function(){
    var pipeline = new Pipeline();
    
    pipeline.add([
        config.bowerDir + '/jquery/dist/jquery.js',
        config.assetsDir + '/js/main.js'
    ], 'site.js');
    
    return pipeline.run(app.addScript);
});

gulp.task('fonts', function(){
    return app.copy( 
        config.bowerDir + '/font-awesome/fonts/*', 
        'web/fonts'
    );
});

gulp.task('clean', function(){
    del.sync( config.revManifestPath );
    del.sync('web/css/*');
    del.sync('web/js/*');
    del.sync('web/fonts/*');
});

gulp.task('watch', function(){     
    gulp.watch(config.assetsDir + '/' + config.sassPattern, ['styles']);
    gulp.watch(config.assetsDir + '/js/**/*.js', ['scripts']);
});


gulp.task('default', ['clean', 'styles', 'scripts', 'fonts', 'watch']);

// ***************************************************************************** PIPELINE functions

var Pipeline = function() {
    this.entries = [];
};
Pipeline.prototype.add = function() {
    this.entries.push(arguments);
};
Pipeline.prototype.run = function(callable) {
    var deferred = Q.defer();
    var i = 0;
    var entries = this.entries;
    var runNextEntry = function() {
        // see if we're all done looping
        if (typeof entries[i] === 'undefined') {
            deferred.resolve();
            return;
        }
        // pass app as this, though we should avoid using "this"
        // in those functions anyways
        callable.apply(app, entries[i]).on('end', function() {
            i++;
            runNextEntry();
        });
    };
    runNextEntry();
    return deferred.promise;
};

