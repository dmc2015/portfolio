// 'use strict';
//
// var gulp = require('gulp');
// var $ = require('gulp-load-plugins')();
// gulp.task('default', function () {
//   return gulp.src('bower_components/foundation/scss/foundation.scss')
//     .pipe($.rubySass({style: 'expanded'}))
//     .pipe(gulp.dest('.tmp/styles'))
//     .pipe($.size());
// });



// 'use strict';
//
// var gulp = require('gulp');
// var sass = require('gulp-sass');
//
// gulp.task('default', function () {
//   // gulp.src('./bower_components/foundation/scss/foundation/foundation.scss')
//   gulp.src('.css/media-queries.scss')
//     .pipe(sass().on('error', sass.logError))
//     .pipe(gulp.dest('.'));
// });



'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('default', function () {
   gulp.src('./css/scss/foundation/foundation.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./css/main/'));
});
