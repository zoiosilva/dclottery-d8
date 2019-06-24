'use strict';

const { watch, dest, src, series, parallel } = require('gulp');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const sassGlob = require('gulp-sass-glob');
const postcss = require('gulp-postcss');
const del = require('del');
const config = require('./patternlab-config.json');
const patternlab = require('@pattern-lab/core')(config);

function buildStyles() {
  return src('*.scss', { cwd: './source' })
    .pipe(sassGlob())
    .pipe(sourcemaps.init())
    .pipe(sass({
      includePaths: ['./node_modules/breakpoint-sass/stylesheets'],
      precision: 10
    }))
    .pipe(
      postcss([
        require('postcss-assets')(),
        require('autoprefixer')({
          remove: false,
        }),
      ]),
    )
    .pipe(sourcemaps.write('.'))
    .pipe(dest('css'));
}

function cleanPatternlab() {
  return del([
    'pattern-lab/public',
  ]);
}

function buildPatternlab() {
  return patternlab.build({cleanPublic: true, watch: false});
}

function fileWatch() {
  watch(
    ['source/**/*.scss', 'images/*.svg'],
    { usePolling: true, interval: 1500 },
    buildStyles
  );
  watch(
    'source/**/*.{twig,json,yaml,yml,md}',
    { usePolling: true, interval: 1500 },
    series(
      cleanPatternlab,
      buildPatternlab
    ),
  );
}

const copycatBuildPatternlab = exports.copycatBuildPatternlab = series(cleanPatternlab, buildPatternlab);
const copycatBuildStyles = exports.copycatBuildStyles = buildStyles;
const copycatBuild = exports.copycatBuild = parallel(copycatBuildStyles, copycatBuildPatternlab);
const copycatWatch = exports.copycatWatch = fileWatch;

exports.default = series(
  copycatBuild,
  copycatWatch
);
