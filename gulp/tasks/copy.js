var gulp = require('gulp');
var paths = require('../consts/paths');
var copies = require('../consts/copy_loaders');

gulp.task('copy', function() {
    return gulp.src(copies)
        .pipe(gulp.dest(paths.dest.js + 'vendor/'));
});
