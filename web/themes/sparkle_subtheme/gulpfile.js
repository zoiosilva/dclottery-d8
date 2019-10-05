'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var livereload = require('gulp-livereload');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var sassGlob = require('gulp-sass-glob');
var sassLint = require('gulp-sass-lint');
var cssComb = require('gulp-csscomb');

var sassDest = 'sass/';
var cssDest = 'css/';
var sassMain = sassDest + 'main.scss';

// get all sass files but exclude any vendor sass inside of sass/
var sassSrc = [sassDest + '**/*.s+(a|c)ss', '!'+ sassDest +'/vendor/**'];

// same as above, but also exclude main.scss and _settings.scss so
// comb doesn't insert hard imports for every scss file.
var sassSrcComb = [sassDest +'**/*.s+(a|c)ss', '!'+ sassMain, '!'+ sassDest +'_settings.scss', '!'+ sassDest +'/vendor/**'];

function css() {
	return gulp
			.src( sassMain )
			.pipe( sassGlob() )
			.pipe( sourcemaps.init() )
			.pipe( sass({outputStyle: 'expanded'}).on('error', sass.logError) )
			.pipe( autoprefixer({
				cascade: false }) )
			.pipe( sourcemaps.write('./sourcemaps') )
			.pipe( gulp.dest(cssDest) );
}

function lint() {
	return gulp.src( sassSrc )
			.pipe( sassGlob() )
			.pipe( sassLint({
				maxBuffer: 1228800,
				configFile: './.sass-lint.yml'
			}) )
			.pipe( sassLint.format() )
			.pipe( sassLint.failOnError() )
			.pipe( sourcemaps.init() )
			.pipe( sass({outputStyle: 'expanded'}).on('error', sass.logError) )
			.pipe( autoprefixer({
				cascade: false }) )
			.pipe( sourcemaps.write('./sourcemaps') )
			.pipe( gulp.dest(cssDest) );
}

function comb()  {
	return gulp.src( sassSrcComb )
			.pipe( sassGlob() )
			.pipe( cssComb() )
			.pipe( gulp.dest( sassDest ) );
}

function cssReload() {
	gulp.src(sassMain)
			.pipe( sassGlob() )
			.pipe( sourcemaps.init() )
			.pipe( sass({outputStyle: 'expanded'}).on('error', sass.logError) )
			.pipe( autoprefixer({
				cascade: false }) )
			.pipe( sourcemaps.write('./sourcemaps') )
			.pipe( gulp.dest(cssDest) )
			.pipe( livereload() );
}

function watch() {
	gulp.watch(sassSrc, { interval: 10 }, ['css']);
}

function watchReload() {
	livereload.listen();
	gulp.watch(sassSrc, { interval: 10 }, ['css-reload']);
}

const compile = gulp.series(comb, lint);

exports['css-reload'] = cssReload;
exports.comb = comb;
exports.compile = compile;
exports.watch = watch;
exports.reload = watchReload;
exports.livereload = watchReload;
exports['watch-reload'] = watchReload;
exports.lint = lint;
exports.css = css;
exports.default = css;
