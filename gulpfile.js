var gulp = require('gulp'),
    less = require('gulp-less'),
    clean = require('gulp-rimraf'),
    concatJs = require('gulp-concat'),
    minifyJs = require('gulp-uglify');


gulp.task('clean', function () {
    return gulp.src(['web/css/*', 'web/js/*', 'web/images/*', 'web/fonts/*', 'web/admin/*'])
        .pipe(clean());
});

gulp.task('admin-less', function() {
    return gulp.src(['web-src/admin/less/*.less'])
        .pipe(less({compress: true}))
        .pipe(gulp.dest('web/admin/css/'));
});

gulp.task('admin-js', function() {
    return gulp.src([
            'bower_components/jquery/dist/jquery.js',
            'bower_components/bootstrap/dist/js/bootstrap.js',
            'bower_components/AdminLTE/dist/js/app.js'
        ])
        .pipe(concatJs('app.js'))
        .pipe(minifyJs())
        .pipe(gulp.dest('web/admin/js/'));
});

gulp.task('admin-fonts', function () {
    return gulp.src(['bower_components/font-awesome/fonts/*',
                     'bower_components/bootstrap/fonts/*'])
        .pipe(gulp.dest('web/admin/fonts/'))
});


gulp.task('default', ['clean'], function () {
    var tasks = ['admin-less','admin-js', 'admin-fonts'];
    tasks.forEach(function (val) {
        gulp.start(val);
    });
});


//gulp.task('watch', function () {
//    var less = gulp.watch('web-src/less/*.less', ['less']),
//        js = gulp.watch('web-src/js/*.js', ['pages-js']);
//});
