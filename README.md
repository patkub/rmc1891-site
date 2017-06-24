# Rogers Manufacturing Company Website

[![Build Status](https://travis-ci.com/patkub/rmc1891-site.svg?token=SzEUzz2hzombHeznyA1S&branch=master)](https://travis-ci.com/patkub/rmc1891-site)

Built with [Polymer 2](https://www.polymer-project.org/) and [Bootstrap 4](http://getbootstrap.com/)

[Polymer 2](https://www.polymer-project.org/) is a JavaScript library that helps you create custom reusable HTML elements, and use them to build performant, maintainable apps.

[Bootstrap 4](http://getbootstrap.com/) is the most popular HTML, CSS, and JS framework for developing responsive, mobile first projects on the web.

### Setup

Install [Node.js](https://nodejs.org/en/download) and [Yarn](https://yarnpkg.com/lang/en/docs/install)

### Fetch dependencies

```sh
yarn install
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

This command serves the app at http://127.0.0.1:8081 and provides basic URL
routing for it:

```sh
yarn run serve:local
```

This command serves the production, minified version of the app at http://127.0.0.1:8080:

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
