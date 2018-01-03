/**
 * RMC gulpfile
 *
 * @author Patrick Kubiak
 */

/* global require Promise */

'use strict';

// include gulp & tools
import gulp from 'gulp';
import gulpif from 'gulp-if';
import del from 'del';
import log from 'fancy-log';
import rename from 'gulp-rename';
import replace from 'gulp-replace';
import inlineSource from 'gulp-inline-source';
import header from 'gulp-header';
import mergeStream from 'merge-stream';
import imagemin from 'gulp-imagemin';

// stylesheet tools
import sass from 'gulp-sass';
import purifyCSS from 'gulp-purifycss';
import cleanCSS from 'gulp-clean-css';
import stripCSSComments from 'gulp-strip-css-comments';

// minification tools
const htmlmin = require('gulp-htmlmin');
const cssSlam = require('css-slam').gulp;
const uglifyes = require('gulp-uglifyes');
const babel = require('gulp-babel');

// configs
import polymerJson from './polymer.json';
import swPrecacheConfig from './sw-precache-config.js';
import pkg from './package.json';

// polymer build
const polymerBuild = require('polymer-build');
const polymerProject = new polymerBuild.PolymerProject(polymerJson);
import workbox from 'workbox-build';

// build and public_html directories
const buildDirectory = 'build';
const buildHTMLDirectory = buildDirectory + '/public_html';

// browsersync
import browserSync from 'browser-sync';
import historyFallback from 'connect-history-api-fallback';
const reload = browserSync.reload;

// sftp
import sftp from 'gulp-sftp';
import minimist from 'minimist';

// header
const banner = ['<!--',
  '<%= pkg.name %> - <%= pkg.description %>',
  '@version v<%= pkg.version %>',
  '@license <%= pkg.license %>',
  '@copyright 2017 The Rogers Manufacturing Company',
  '@link <%= pkg.homepage %>',
  '@github <%= pkg.repository.url %>',
  '-->',
  ''].join('\n');

/**
 * Waits for the given ReadableStream.
 * @param {ReadableStream} stream The given stream.
 * @return {Promise}.
 */
function waitFor(stream) {
  return new Promise((resolve, reject) => {
    stream.on('end', resolve);
    stream.on('error', reject);
  });
}

// Compile & Minify Stylesheets
gulp.task('css', function() {
  return gulp.src('app/scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(purifyCSS([
      'node_modules/bootstrap/dist/js/bootstrap.min.js',
      'app/src/**/*.html',
      'index.html',
    ]))
    .pipe(stripCSSComments({
      preserve: false,
    }))
    .pipe(cleanCSS({
      level: 2,
    }))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('app/css/'));
});

/**
 * Polymer build.
 * @return {Promise}.
 */
function build() {
  return new Promise((resolve, reject) => {
    // Lets create some inline code splitters in case you need them later in your build.
    let sourcesStreamSplitter = new polymerBuild.HtmlSplitter();
    let dependenciesStreamSplitter = new polymerBuild.HtmlSplitter();
    let buildStreamSplitter = new polymerBuild.HtmlSplitter();

    // Okay, so first thing we do is clear the build directory
    log(`Deleting ${buildDirectory} directory...`);
    del([buildDirectory])
      .then(() => {
        // Let's start by getting your source files. These are all the files
        // in your `src/` directory, or those that match your polymer.json
        // "sources"  property if you provided one.
        let sourcesStream = polymerProject.sources()

          // If you want to optimize, minify, compile, or otherwise process
          // any of your source code for production, you can do so here before
          // merging your sources and dependencies together.
          .pipe(gulpif(/\.(png|gif|jpg|svg)$/, imagemin()))
            
          // Inline critical path CSS
          .pipe(gulpif('index.html', inlineSource()))
          
          // The `sourcesStreamSplitter` created above can be added here to
          // pull any inline styles and scripts out of their HTML files and
          // into seperate CSS and JS files in the build stream. Just be sure
          // to rejoin those files with the `.rejoin()` method when you're done.
          .pipe(sourcesStreamSplitter.split())
          
          // Minify JS
          .pipe(gulpif(/\.js$/, babel({
            presets: ['es2015'],
          })))
          
          .pipe(gulpif(/\.js$/, uglifyes({
            warnings: true,
            ecma: 8,
          })))

          // Minify CSS
          .pipe(gulpif(/\.(html|css)$/, cssSlam()))

          // Minify HTML
          .pipe(gulpif(/\.html$/, htmlmin({
            collapseWhitespace: true,
            conservativeCollapse: true,
            removeAttributeQuotes: true,
            removeComments: true,
          })))

          // Remember, you need to rejoin any split inline code when you're done.
          .pipe(sourcesStreamSplitter.rejoin());


        // Similarly, you can get your dependencies seperately and perform
        // any dependency-only optimizations here as well.
        let dependenciesStream = polymerProject.dependencies()
          .pipe(dependenciesStreamSplitter.split())
          // Add any dependency optimizations here.
          .pipe(dependenciesStreamSplitter.rejoin());


        // Okay, now let's merge your sources & dependencies together into a single build stream.
        let buildStream = mergeStream(sourcesStream, dependenciesStream)
          .once('data', () => {
            log('Analyzing build dependencies...');
          });
        
        buildStream = buildStream
          // If you want bundling, pass the stream to polymerProject.bundler.
          // This will bundle dependencies into your fragments so you can lazy
          // load them.
          .pipe(polymerProject.bundler())
          
          // Split build stream
          .pipe(buildStreamSplitter.split())
          
          // Minify JS
          .pipe(gulpif(/\.js$/, babel({
            presets: ['es2015'],
          })))
          
          .pipe(gulpif(/\.js$/, uglifyes({
            warnings: true,
            ecma: 8,
          })))
          
          // Minify CSS
          .pipe(gulpif(/\.(html|css)$/, cssSlam()))

          // Minify HTML
          .pipe(gulpif(/\.html$/, htmlmin({
            collapseWhitespace: true,
            conservativeCollapse: true,
            removeAttributeQuotes: true,
            removeComments: true,
          })))
          
          // Add header
          .pipe(gulpif('index.html', header(banner, {pkg: pkg})))
          
          // Rejoin build stream
          .pipe(buildStreamSplitter.rejoin())
        
          // Include the Custom Elements ES5 Adapter
          .pipe(polymerProject.addCustomElementsEs5Adapter())
        
          // Now let's generate the HTTP/2 Push Manifest
          .pipe(polymerProject.addPushManifest())
        
          // Okay, time to pipe to the build directory
          .pipe(gulp.dest(buildHTMLDirectory));

        // waitFor the buildStream to complete
        return waitFor(buildStream);
      })
      .then(() => {
        // Okay, now let's generate the Service Worker
        log('Generating the Service Worker...');
        return workbox.generateSW({
          globDirectory: buildHTMLDirectory,
          globPatterns: ['**\/*.{html,js,css}', 'node_modules/**/*'],
          globIgnores: [],
          runtimeCaching: [{
            // You can use a RegExp as the pattern:
            urlPattern: '/api/*',
            handler: 'cacheFirst',
            // Any options provided will be used when
            // creating the caching strategy.
            options: {
              cacheName: 'api',
              cacheExpiration: {
                maxEntries: 10,
              },
            },
          }],
          navigateFallback: 'index.html',
          swDest: buildHTMLDirectory + '/sw.js',
          clientsClaim: true,
          skipWaiting: true
        }).then(() => {
          console.info('Service worker generation completed.');
        }).catch((error) => {
          console.warn('Service worker generation failed: ' + error);
        });
      })
      .then(() => {
        // Copy app indexeddb mirror worker
        log('Copying app indexeddb mirror worker...');
        return gulp.src([
          'bower_components/app-storage/app-indexeddb-mirror/app-indexeddb-mirror-worker.js',
          'bower_components/app-storage/app-indexeddb-mirror/common-worker-scope.js',
        ]).pipe(gulp.dest(buildHTMLDirectory));
      })
      .then(() => {
        // Copy vendor
        log('Copying composer php dependencies...');
        return gulp.src([
          'vendor/**/*',
          'vendor/**/.*',
        ]).pipe(gulp.dest(buildDirectory + '/vendor'));
      })
      .then(() => {
        // Copy api lib
        log('Copying api lib...');
        return gulp.src([
          'api/lib/**/*',
          'api/lib/**/.*',
        ]).pipe(gulp.dest(buildDirectory + '/api/'));
      })
      .then(() => {
        // Copy api public
        log('Copying api public...');
        return gulp.src([
          'api/public/**/*',
          'api/public/**/.*',
        ]).pipe(gulp.dest(buildHTMLDirectory + '/api/'));
      })
      .then(() => {
        // You did it!
        log('Build complete!');
        resolve();
      });
  });
}

/**
 * Defines the list of resources to watch for changes.
 */
function watch() {
  gulp.watch([
    'app/scss/**/*.scss',
  ], [
    'css',
    reload,
  ]);
  
  gulp.watch([
    'app/images/**/*',
    'app/src/**/*',
    'index.html',
  ], reload);
}

/**
 * Watch resources for changes.
 */
gulp.task('watch', function() {
  watch();
});

/**
 * Serves local landing page from "./" directory.
 */
gulp.task('serve:browsersync:local', () => {
  browserSync({
    server: {
      baseDir: '.',
      middleware: [
        historyFallback(),
      ],
    },
    port: 3000,
    ui: {
      port: 3001,
    },
    browser: 'chrome',
  });

  watch();
});

/**
 * Serves production landing page from buildHTMLDirectory directory.
 */
gulp.task('serve:browsersync:build', () => {
  browserSync({
    server: {
      baseDir: buildHTMLDirectory,
      middleware: [
        historyFallback(),
      ],
    },
    port: 3002,
    ui: {
      port: 3003,
    },
    browser: 'chrome',
  });
});

/**
 * Database connection info
 */
gulp.task('deploy:db', function() {
  /* global process */
  let args = minimist(process.argv.slice());
  
  // replace database connection info
  return gulp.src(buildDirectory + '/api/config.ini')
    .pipe(replace('{{host}}', args.dbhost))
    .pipe(replace('{{name}}', args.dbname))
    .pipe(replace('{{user}}', args.dbuser))
    .pipe(replace('{{pass}}', args.dbpass))
    .pipe(gulp.dest(buildDirectory + '/api/'));
});

/**
 * Deploys files.
 */
gulp.task('deploy:files', function() {
  /* global process */
  let args = minimist(process.argv.slice());
  
  // copy files
  return gulp.src([
    buildDirectory + '/**/*',
    buildDirectory + '/**/.*',
  ]).pipe(sftp({
    host: args.host,
    user: args.user,
    pass: args.pass,
    remotePath: '/',
  }));
});

// Browsersync serve
gulp.task('serve:local', ['css', 'serve:browsersync:local']);
gulp.task('serve:build', ['serve:browsersync:build']);

// Build
gulp.task('build', ['css'], build);
