var gulp = require('gulp');
var concat = require('gulp-concat');

var paths = require('../consts/paths.js');
var vendors = require('../consts/vendors.js');

gulp.task('vendor-concate', function () {
    return gulp.src(vendors)
        .pipe(concat('vendors.js'))
        .pipe(gulp.dest(paths.dest.js + 'vendor'));
});