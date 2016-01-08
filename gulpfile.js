var gulp = require('gulp'),
    less = require('gulp-less'),
    clean = require('gulp-rimraf'),
    concatJs = require('gulp-concat'),
    minifyJs = require('gulp-uglify');


gulp.task('clean', function () {
    return gulp.src(['web/css/*', 'web/js/*', 'web/images/*', 'web/fonts/*', 'web/admin/*'])
        .pipe(clean());
});

//gulp.task('admin-sty;e', function () {
//    return gulp.src([
//            'bower_components/AdminLTE/dist/css/AdminLTE.min.css',
//            'bower_components/AdminLTE/dist/css/_all-skins.min.css',
//            'bower_components/font-awesome/css/font-awesome.min.css'
//        ])
//        .pipe(gulp.dest('web/admin/'));
//});

gulp.task('admin-less', function() {
    return gulp.src(['web-src/admin/less/*.less'])
        .pipe(less({compress: true}))
        .pipe(gulp.dest('web/admin/css/'));
});

gulp.task('default', ['clean'], function () {
    var tasks = ['admin-less'];
    tasks.forEach(function (val) {
        gulp.start(val);
    });
});


//gulp.task('watch', function () {
//    var less = gulp.watch('web-src/less/*.less', ['less']),
//        js = gulp.watch('web-src/js/*.js', ['pages-js']);
//});
