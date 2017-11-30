# Rogers Manufacturing Company Website

[![GitHub version](https://badge.fury.io/gh/patkub%2Frmc1891-site.svg)](https://badge.fury.io/gh/patkub%2Frmc1891-site)
[![Circle CI](https://circleci.com/gh/patkub/rmc1891-site.svg?style=shield&circle-token=de28773432748e5c69215e589d0d1f18eb46491a)](https://circleci.com/gh/patkub/rmc1891-site)
[![Travis CI](https://travis-ci.org/patkub/rmc1891-site.svg?branch=master)](https://travis-ci.org/patkub/rmc1891-site)
[![Test Coverage](https://codeclimate.com/github/patkub/rmc1891-site.svg)](https://codeclimate.com/github/patkub/rmc1891-site)
[![Dependency Status](https://david-dm.org/patkub/rmc1891-site/status.svg)](https://david-dm.org/patkub/rmc1891-site)
[![devDependency Status](https://david-dm.org/patkub/rmc1891-site/dev-status.svg)](https://david-dm.org/patkub/rmc1891-site?type=dev)
![Greenkeeper](https://badges.greenkeeper.io/patkub/rmc1891-site.svg)  

> The Rogers Manufacturing Company website.

[Docs](https://patkub.github.io/rmc1891-site/) | [Polymer 2](https://www.polymer-project.org/) | [Bootstrap 4](https://getbootstrap.com/) | [Slim 3](https://www.slimframework.com/)

[Polymer 2](https://www.polymer-project.org/) is a JavaScript library that helps you create custom reusable HTML elements, and use them to build performant, maintainable apps.

[Bootstrap 4](http://getbootstrap.com/) is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web.

[Slim 3](https://www.slimframework.com/) is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.

### Table of Contents
* [Setup](#setup)
* [Fetch dependencies](#fetch-dependencies)
* [Build](#build)
* [Start the development server](#start-the-development-server)
* [Run tests](#run-tests)
* [Linting](#linting)
* [Documentation](#documentation)
* [Publish](#publish)

### Setup

First, install [Node.js](https://nodejs.org/en/download), [Yarn](https://yarnpkg.com/lang/en/docs/install), and [Composer](https://getcomposer.org/)

Second, install [Bower](https://bower.io/) using [npm](https://www.npmjs.com)

```sh
npm install -g bower
```

### Fetch dependencies

```sh
yarn install
bower install
composer install
```

### Build

This command performs HTML, CSS, and JS minification on the application
dependencies, and generates a service worker `sw.js` file with code to pre-cache the
dependencies based on the entrypoint and fragments specified in `polymer.json`.
The minified files are output to the `build/public_html` folder,
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

Tests are run on Chrome in headless mode on Travis CI and Sauce Labs using CircleCI.

This command will run [Web Component Tester](https://github.com/Polymer/web-component-tester)
against the chrome browser currently installed on your machine:

```sh
yarn run test:local
```

If running Windows you will need to set the following environment variables:

- LAUNCHPAD_BROWSERS
- LAUNCHPAD_CHROME

Read More here [daffl/launchpad](https://github.com/daffl/launchpad#environment-variables-impacting-local-browsers-detection)

This command will run [Web Component Tester](https://github.com/Polymer/web-component-tester)
against the chrome browser on Sauce Labs:

```sh
yarn run test:sauce
```

### Linting

This command will run [ESLint](http://eslint.org/) on JavaScript and HTML files,
[stylelint](https://stylelint.io/) on HTML files, and `polymer lint`:

```sh
yarn run lint
```

### Documentation

This command will generate a documentation page for the elements:

```sh
yarn run docs
```

### Publish

This script will publish a new version on Github based on the `version` field
in the `package.json` file. It will create a new branch and tag for the release.

```sh
sh scripts/publish.sh
```
