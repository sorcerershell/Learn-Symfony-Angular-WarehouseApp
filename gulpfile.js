var gulp        = require('gulp'),
    sass        = require('gulp-sass'),
    notify      = require('gulp-notify'),
    sourcemaps  = require('gulp-sourcemaps'),
    concat      = require('gulp-concat'),
    bower       = require('gulp-bower');

var config = {
    bowerDir: './web/app/bower_components',
    nodeDir: './node_modules',
    assetPath: './web/assets',
    outputDir: './web/app/assets',
}
gulp.task('bower', function() {
    return bower()
        .pipe(gulp.dest(config.bowerDir))
});


gulp.task('icons', function() {
    return gulp.src(config.bowerDir + '/font-awesome/fonts/**.*')
        .pipe(gulp.dest(config.outputDir + '/fonts'));
});

gulp.task('default', ['bower', 'icons', 'css']);

gulp.task('css', function () {
    return gulp.src(config.assetPath + '/scss/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyles: 'compressed',
            includePaths: [
                config.assetPath +  '/scss',
                config.bowerDir + '/bootstrap-sass/assets/stylesheets',
                config.bowerDir + '/font-awesome/scss'
            ]
        }))
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(config.outputDir + '/css'));
});

gulp.task('watch', function () {
    gulp.watch(config.assetPath + '/assets/sass/**/*.scss', ['sass']);
});


// Minify Plugins CSS files
gulp.task('minifyPlugins', function() {
    return gulp.src([config.bowerDir + '/bootstrap/dist/css/bootstrap.css'])
        .pipe(rename({
            suffix: ".min",
            extname: ".css"
        }))
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('web/css'));
});

gulp.task('build', ['minifyPlugins']);
gulp.task('default', ['watch']);