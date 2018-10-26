var gulp = require('gulp');
var uglify = require('gulp-uglify');
var browserify = require('gulp-browserify');
var babelify = require('babelify');

gulp.task('js', function() {

  return gulp.src('./src/app.js')
    .pipe(browserify({
      transform: [babelify.configure({
        presets: ["es2015", "react"],
        plugins: ["transform-object-rest-spread", "transform-decorators-legacy"]
      })]
    }))
    //.pipe(uglify())
    .pipe(gulp.dest('./build'));

});
/*
gulp.task('apply-prod-environment', function() {
    process.env.NODE_ENV = 'production';
});
*/
gulp.task('default', [/*'apply-prod-environment',*/ 'js'], function() {
  gulp.watch('./src/**/*.js', ['js']);
});