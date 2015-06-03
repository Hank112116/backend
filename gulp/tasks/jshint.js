var gulp   = require('gulp');
var jshint = require('gulp-jshint');
gulp.task('jshint', function() {
  return gulp.src([
        './front/js/*.js',
        './front/js/libs/*.js'
    ])
    .pipe(jshint.extract('vendor|react'))
    .pipe(jshint({lookup:true}))
    .pipe(jshint.reporter('jshint-stylish'));
});