var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

var config = {
    assetsDir: 'app/Resources/assets',
    sassPattern: 'sass/**/*.scss'
};

gulp.task('sass', function(){
    gulp.src(config.assetsDir + '/' + config.sasPattern)
        .pipe(sourcemaps.init()) // Points to original scss file errors in console debugger, not css
        .pipe(sass())
        .pipe(sourcemaps.write('.')) // Points to original scss file errors in console debugger, not css
        .pipe(gulp.dest('web/css'))
    ;
});

gulp.task('watch', function(){
    gulp.watch(config.assetsDir + '/' + config.sasPattern, ['sass']);
});

gulp.task('default', ['sass', 'watch']);

