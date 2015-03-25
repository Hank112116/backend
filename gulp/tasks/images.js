var gulp       = require('gulp');
var changed    = require('gulp-changed');
var imagemin   = require('gulp-imagemin');

var paths = require('../consts/paths.js');

gulp.task('images', function() {
	var dest = paths.dest.images,
        images = paths.src.images + '**/**';

	return gulp.src(images)
		.pipe(changed(dest)) // Ignore unchanged files
		.pipe(imagemin()) // Optimize
		.pipe(gulp.dest(dest));
});
