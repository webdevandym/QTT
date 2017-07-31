'use strick'
const gulp = require('gulp')
var rename = require("gulp-rename"),
  concat = require('gulp-concat'),
  uglifyjs = require('uglify-js'),
  deletefile = require('gulp-delete-file'),
  composer = require('gulp-uglify/composer'),
  uglify = require('gulp-uglify'),
  livereload = require('gulp-livereload'),
  notify = require('gulp-notify'),
  connect = require('gulp-connect'),
  wait = require('gulp-wait2');
//style paths

var paths = {
  sassFiles: 'assets/css/sass/**/*.scss',
  cssDest: 'assets/css/css/',
  jsLoc: 'assets/js/*.*',
  jsmin: 'assets/js/min/',
  jsminconc: 'assets/js/concat/',
  jsBabel: 'assets/js/babel/',
  cssFile: ['assets/css/**/*.css', '!assets/css/mincss/*.css', '!assets/css/css/advStyles.css']
};

gulp.task('scss', ['deleteFile'], function() {
  var sass = require('gulp-sass');

  return gulp.src(paths.sassFiles)
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest(paths.cssDest));
});

gulp.task('deleteFile', function() {

  return gulp.src('./assets/css/mincss/**/*.css')
    .pipe(deletefile({
      deleteMatch: true
    }))
})

gulp.task('autoprefixer_clear', ['scss'], function() {
  var postcss = require('gulp-postcss');
  var autoprefixer = require('autoprefixer');


  return gulp.src(['./assets/css/**/*.css', '!./assets/css/mincss/*.css'])
    .pipe(postcss([autoprefixer({
      browsers: ['Last 2 versions', '> 5%', 'IE 8', 'Last 5 Firefox versions']
    })]))
    .pipe(concat('siteStyle.css'))
    .pipe(gulp.dest('./assets/css/mincss/'));
});

gulp.task('unCSS', ['autoprefixer_clear'], function() {

  var cssnano = require('gulp-cssnano');


  return gulp.src('./assets/css/mincss/siteStyle.css')
    .pipe(cssnano({
      discardComments: {
        removeAll: true,
        convertValues: true,
        discardDuplicates: true,
        functionOptimiser: true,
        mergeLonghand: true,
        colormin: true,
        discardOverridden: true,
        uniqueSelectors: true,
        convertValues: true,
        discardEmpty: true,


      }
    }))
    .pipe(rename('siteStyle-min.css'))
    .pipe(gulp.dest('./assets/css/mincss/'))
    .pipe(wait(150))
    .pipe(livereload())
    .pipe(notify("CSS -> DONE!!!"));

});


////JS
gulp.task('JS', ['compressJS'], () => {
  gulp.src('')
    .pipe(notify("JS -> DONE!!!"));
})

gulp.task('compressJS', ['concatJS'], function(cb) {
  var pump = require('pump'),
    minify = composer(uglifyjs, console);

  pump([
      gulp.src([paths.jsminconc + '*.*', paths.jsBabel + '/firstpagescripts.js']),
      minify({
        compress: {
          sequences: true,
          properties: true, // optimize property access: a["foo"] → a.foo
          dead_code: true, // discard unreachable code
          drop_debugger: true, // discard “debugger” statements
          unsafe: false, // some unsafe optimizations (see below)
          conditionals: true, // optimize if-s and conditional expressions
          comparisons: true, // optimize comparisons
          evaluate: true, // evaluate constant expressions
          booleans: true, // optimize boolean expressions
          loops: true, // optimize loops
          unused: true, // drop unused variables/functions
          hoist_funs: true, // hoist function declarations
          hoist_vars: false, // hoist variable declarations
          if_return: true, // optimize if-s followed by return/continue
          join_vars: true, // join var declarations
          cascade: true, // try to cascade `right` into `left` in sequences
          side_effects: true, // drop side-effect-free statements
          warnings: true, // warn about potentially dangerous optimizations/code
          global_defs: {} // global definitions
        }
      }),
      gulp.dest(paths.jsmin),
      wait(150),
      livereload()
    ],
    cb
  );

});

gulp.task('concatJS', ['babel'], () => {

  return gulp.src(['assets/js/setupParam.js', 'assets/js/moments.js', paths.jsBabel + '*.js', '!' + paths.jsBabel + 'firstpagescripts.js'])
    .pipe(concat('siteJS-min.js'))
    .pipe(gulp.dest(paths.jsminconc));
});

gulp.task('babel', ['deleteJSmin'], () => {
  const babel = require('gulp-babel');

  return gulp.src([paths.jsLoc, '!assets/js/moments.js'])
    .pipe(babel({
      presets: ['es2015']
    }))
    .pipe(gulp.dest(paths.jsBabel));
});

gulp.task('deleteJSmin', () => {
  return gulp.src(['./assets/js/min/*.js', './assets/js/babel/*.js'])
    .pipe(deletefile({
      deleteMatch: true
    }));
})

gulp.task('reloadPHP', () => {
  gulp.src('')
    .pipe(notify('PHP -> reload!'))
    .pipe(wait(150))
    .pipe(livereload());
})

gulp.task('watch', function() {
  livereload.listen({
    host: '127.0.0.1'
  });

  gulp.watch([paths.sassFiles, paths.cssFile], ['unCSS']);
  gulp.watch(paths.jsLoc, ['JS']);
  gulp.watch(['./**/*.php'], ['reloadPHP']);
});

gulp.task('default', ['watch']);
