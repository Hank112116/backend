var gulp = require('gulp');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var babelify = require("babelify");

var _ = require('lodash');

var handleErrors = require('../util/handleErrors');

var paths = require('../consts/paths.js');
var js_loaders = require('../consts/js_loaders.js');

gulp.task('browserify', function () {
    _.map(js_loaders, function (filename) {
        browserify(paths.src.js + filename)
            .transform(babelify)
            .bundle()
            .on('error', handleErrors)
            .pipe(source(filename))
            .pipe(gulp.dest(paths.dest.js));
    });
});
