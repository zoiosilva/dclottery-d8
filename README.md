# Taoti Drupal 8 Composer
This repository provides a base Drupal 8 setup for Taoti sites, using current tooling and best practices.

## New Users

If this is your first time creating or developing site built with the Taoti Drupal 8 Composer system, first ensure that
your computer meets all the [requirements/prerequisites](docs/requirements.md).

## Tooling Overview
Taoti Drupal 8 sites use Docker with Lando to provide a consistent isolated development environment. As well as ease of
use, this also simplifies debugging as the environment is very similar to Pantheon production environments. Some of the
tools provided by Lando that you will be working with include:

### Composer
[Composer](https://getcomposer.org/) is a dependency manager for PHP that allows us to perform a multitude of tasks;
everything from creating a Drupal project to declaring libraries and even installing contributed modules. The advantage
of using Composer is that it allows us to quickly install and update dependencies by simply running a few commands from
a terminal window.

### Node & NPM
[Node](https://nodejs.org/en/) is a cross platform runtime environment for creating server side and networking
applications. JavaScript running outside the browser. [NPM](https://www.npmjs.com/) is the JavaScript package manager
used to install, share, and distribute code and is used to manage most of the front end dependencies in Taoti projects.

use `lando npm` to run npm in the copycat theme folder - for example `lando npm install`.

### Gulp
[Gulp](https://gulpjs.com/) is a JavaScript task runner that allows us to perform repetitive tasks like minification,
compilation, unit testing, linting and more. `Gulp` is used to compile Sass, Pattern Lab and watch for file changes
during development.

Use `lando gulp` to run gulp in the copycat theme folder. `lando gulp` will build and then watch for changes in the
theme files.

## Creating a new site
See [New Site Instructions](docs/new-site.md) for full details on first time set up for a new Taoti Drupal 8 composer
site.

## Initialize Site for Development

 - Ensure your computer is set up/meets [requirements](docs/requirements.md)

 - Checkout and initialize your new site:
 ```bash
    git checkout [repo address]
    cd [local repo folder]
    lando start
```
If this is your first Lando based site, initialization will be somewhat slow as initial requirements are downloaded.
When done, a message like this should display:
```
BOOMSHAKALAKA!!!

Your app has started up correctly.
Here are some vitals:

 NAME                  drupal8
 LOCATION              /var/www/drupal8
 SERVICES              appserver_nginx, appserver, database, cache, edge, edge_ssl, index
 APPSERVER_NGINX URLS  https://localhost:32772
                       http://localhost:32773
 EDGE URLS             http://localhost:32775
                       http://drupal8.lndo.site:8000
                       https://drupal8.lndo.site
 EDGE_SSL URLS         https://localhost:32776
```
For most local development purposes, you will want to use the SSL url that ends with `.lndo.site` (
`https://drupal8.lndo.site` in this example). Some of these other URLs might be useful if you're having caching issues.

- Pull the latest database and files. Depending on the site, for example if it has a lot of files you may prefer to not
download files; if so, just pass `--files=none` instead.
```bash
    lando pull --code=none --database=dev --files=dev
```

- Scaffold your Pattern Lab instance and compile the SASS files to css for our theme. This will also start the file
watcher to recompile any theme files as needed.
```bash
    lando npm install
    lando gulp
```
