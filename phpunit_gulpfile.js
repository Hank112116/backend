var gulp = require('gulp');
var shell = require('gulp-shell');

gulp.task('phpunit', function() {
    return gulp.src('').pipe(shell('phpunit', {ignoreErrors: true}));
});

gulp.task('watch', function() {
    gulp.watch(['app/**/*.php'], ['phpunit'])
});

gulp.task('default', [
    'phpunit',
    'watch'
]);