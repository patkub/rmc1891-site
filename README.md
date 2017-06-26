# Rogers Manufacturing Company Website

[![GitHub version](https://badge.fury.io/gh/patkub%2Frmc1891-site.svg)](https://badge.fury.io/gh/patkub%2Frmc1891-site)
[![Build Status](https://travis-ci.org/patkub/rmc1891-site.svg?branch=master)](https://travis-ci.org/patkub/rmc1891-site)  
[![Build Status](https://saucelabs.com/browser-matrix/patrykkub.svg)](https://saucelabs.com/beta/builds/5f4dfefe50f24f74b8973a69bb1d7e5c)

Built with [Polymer 2](https://www.polymer-project.org/) and [Bootstrap 4](http://getbootstrap.com/)

[Polymer 2](https://www.polymer-project.org/) is a JavaScript library that helps you create custom reusable HTML elements, and use them to build performant, maintainable apps.

[Bootstrap 4](http://getbootstrap.com/) is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web.

### Setup

First, install [Node.js](https://nodejs.org/en/download) and [Yarn](https://yarnpkg.com/lang/en/docs/install)

Second, install [Bower](https://bower.io/) using [npm](https://www.npmjs.com)

```sh
npm install -g bower
```

### Fetch dependencies

```sh
yarn install
bower install
```

### Build

This command performs HTML, CSS, and JS minification on the application
dependencies, and generates a service-worker.js file with code to pre-cache the
dependencies based on the entrypoint and fragments specified in `polymer.json`.
The minified files are output to the `build/bundled` folder,
generated using fragment bundling.

```sh
yarn run build
```

### Start the development server

This command serves the app at http://127.0.0.1:3000 and Browsersync UI at
http://127.0.0.1:3001:

```sh
yarn run serve:local
```

This command serves the production, minified version of the app at
http://127.0.0.1:3000 and Browsersync UI at http://127.0.0.1:3001:

```sh
yarn run serve:build
```

### Run tests

This command will run [Web Component Tester](https://github.com/Polymer/web-component-tester)
against the browsers currently installed on your machine:

```sh
yarn run test
```

If running Windows you will need to set the following environment variables:

- LAUNCHPAD_BROWSERS
- LAUNCHPAD_CHROME

Read More here [daffl/launchpad](https://github.com/daffl/launchpad#environment-variables-impacting-local-browsers-detection)

### Linting

This command will run [ESLint](http://eslint.org/) on JavaScript and HTML files:

```sh
yarn run lint
```
