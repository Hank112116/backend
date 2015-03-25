var gulp = require('gulp');

var paths = require('../consts/paths');

gulp.task('watch', function() {
    var watch_images = [paths.src.images + '**', paths.src.images + '/**/**'],
        watch_react  = [paths.src.react + '**'],
        watch_js     = [
            paths.src.js + '*.js',
            "!" + paths.src.js + "*-concated.js",
            "!" + paths.src.js_vendor + "**"
        ];

    gulp.watch(watch_images, ['images']);
    gulp.watch(watch_js, ['browserify']);
    gulp.watch(watch_react, ['reactify']);
});
