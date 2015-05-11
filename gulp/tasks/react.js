var gulp = require('gulp');
var browserify = require('browserify');
var reactify = require('reactify');
var source = require('vinyl-source-stream');
var babelify = require("babelify");

var _ = require('lodash');

var bundleLogger = require('../util/bundleLogger');
var handleErrors = require('../util/handleErrors');

var paths = require('../consts/paths.js');
var reacts = require('../consts/react_loaders.js');

gulp.task('reactify', function () {
    _.each(reacts, function (r) {
        bundleLogger.log( r + '.react.js startd' );

        browserify(paths.src.react + r + '.react.js')
            .transform(reactify)
            .transform(babelify)
            .bundle()
            .on('error', handleErrors)
            .pipe(source(r + '.js'))
            .pipe(gulp.dest(paths.dest.react))
            .on('end', function() { bundleLogger.log( r + " done" ); });
    });
});