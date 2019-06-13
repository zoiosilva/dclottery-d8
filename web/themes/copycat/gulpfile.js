"use strict";

// Load plugins
const { src, dest, series, watch, parallel } = require("gulp");
const sass = require("gulp-sass");
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require("gulp-autoprefixer");
const sassGlob = require("gulp-sass-glob");
const livereload = require('gulp-livereload');
const exec = require('child_process');

function css() {
  return src('./pattern-lab/source/*.scss')
    .pipe(sassGlob())
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
    .pipe(autoprefixer({
			overrideBrowserslist: ['last 2 versions'],
      cascade: false }))
    .pipe(sourcemaps.write('./'))
    .pipe(dest('./css'));
}

function pl(cb) {
  // generate Pattern Lab
  sh(`(cd pattern-lab && exec php core/console --generate)`, true, cb);
}

function watchFiles() {
  watch('./pattern-lab/source/*.scss', css),
  watch(["./pattern-lab/source/**/*.twig", "./pattern-lab/source/**/*.json", "./pattern-lab/source/**/*.yaml", "./pattern-lab/source/**/*.yml"], pl);
}

const build = parallel(pl, css);
const setup = series(checkForStarterKit, setupCleanPL, setupInstallPL, setupCopyTwigComponents, setupDeletePLSource, setupMoveStarterKit, setupDisableAutoescape, setupPLComposer, pl, css);
exports.build = build;
exports.css = css;
exports.pl = pl;
exports.watch = watchFiles;
exports.setup = setup;
exports.default = build;

// ---
// setup tasks
// ---

function checkForStarterKit(cb) {
  // fail if the _starter-kit directory doesn't exit.
  sh(`if [ ! -d '_starter-kit' ]; then exit 1; fi`, true, cb);
}

function setupCleanPL(cb) {
  // Remove existing PL if present
  sh(`rm -rf pattern-lab`, true, cb);
}

function setupInstallPL(cb) {
  // Install drupal-standard PL
  sh(`composer create-project pattern-lab/edition-drupal-standard pattern-lab --no-interaction || true`, true, cb);
}

function setupCopyTwigComponents(cb) {
  // Copy over the twig components from the install,
  // and merge into our starter kit.
  sh(`cp -rn pattern-lab/source/_twig-components _starter-kit/`, false, cb);
}

function setupDeletePLSource(cb) {
  // Delete the default source directory
  sh(`rm -rf pattern-lab/source`, true, cb);
}

function setupMoveStarterKit(cb) {
  // Set our starter kit in place as the new source directory.
  sh(`mv _starter-kit pattern-lab/source/`, true, cb);
}

function setupDisableAutoescape(cb) {
  // Disable autoescape of html in pattern-lab's default config.
  sh(`sed -i -e 's/twigAutoescape: html/twigAutoescape: false/g' ./pattern-lab/config/config.yml`, true, cb);
}

function setupPLComposer(cb) {
  // Run PL's composer install
  sh(`(cd pattern-lab && exec composer install --no-interaction || true)`, true, cb);
}

// ---
// helper functions
// ---

// wrapper to run shell commands nicely.
function sh(cmd, exitOnError, cb) {
  const child = exec.exec(cmd, { encoding: 'utf8' });
  let stdout = '';
  let stderr = '';

  child.stdout.on('data', (data) => {
    stdout += data;
    process.stdout.write(data);
  });

  child.stderr.on('data', (data) => {
    stderr += data;
    process.stdout.write(data);
  });

  child.on('close', (code) => {
    if (code > 0 && exitOnError) {
      console.log(`Error with code ${code} after running: ${cmd}`);
      process.exit(code);
    }

    cb();
  });
}
