# Taoti Drupal 8 Composer
This repository provides a base Drupal 8 setup for Taoti sites, using current tooling and best practices.

- [Creating a new site](docs/new-site.md)
- [Converting from an old non-ci site](docs/site-conversion.md)
- [Composer Based CI Upstream](docs/composer-based-custom-upstream.md)
- [New Users](#new-users)
- [Tooling Overview](#tooling-overview)
  * [Composer](#composer)
  * [Node & NPM](#node---npm)
  * [Gulp](#gulp)
  * [Command List](#command-list)
- [Initialize Site for Development](#initialize-site-for-development)
- [Debugging](#debugging)
  * [Deploy Errors](#deploy-errors)
    + [Build was canceled](#build-was-canceled)
    + [Too long with no output (exceeded 10m0s)](#too-long-with-no-output--exceeded-10m0s-)
    + [codeserver.dev.[numbers and dashes]@codeserver.dev.[numbers and dashes].drush.in's password:](#codeserverdevnumbers-and-dashescodeserverdevnumbers-and-dashesdrushins-password)
    + [Client error: `GET https://api.github.com/...](#client-error-get-httpsapigithubcom)
  * [Lando Errors](#lando-errors)
  * [Debugging PHP Issues](#debugging-php-issues)
    + [XDebug](#xdebug)
      - [PHPStorm on Windows](#phpstorm-on-windows)

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

Use `lando npm` to run npm in the sparkle_subtheme theme folder - for example `lando npm install`.

### Gulp
[Gulp](https://gulpjs.com/) is a JavaScript task runner that allows us to perform repetitive tasks like minification,
compilation, unit testing, linting and more. `Gulp` is used to compile Sass, Pattern Lab and watch for file changes
during development.

Use `lando gulp` to run gulp in the sparkle_subtheme theme folder. `lando gulp` will build and then watch for changes in
the theme files.

### Command List:
Lando and our composer file provides a bunch of custom and built in commands. This list some of the commands likely to
be of use during normal development. Commands sorted alphabetically, with the most important in bold.
  - `lando composer`: Runs composer commands. All standard composer commands and those defined in composer.json. Has
  many sub-commands including:
    - `lando composer build-theme`: Builds all theme files (see also `lando gulp`).
    - `lando composer code-sniff`: Locally run PHP sniffs as run by the CI - see [standards](docs/standards.md).
    - `lando composer code-fix`: Run PHPCBF to fix any standards/errors it can automatically.
    - `lando composer d9-check`: Check custom code against Drupal Check for deprecations/errors for Drupal 9.
    - **`lando composer git-hooks`: Enable git hooks to automatically fix standards/auto-fixable errors before commits.
    See [standards](docs/standards.md).**
    - **`lando composer list`: List all composer commands.**
    - `lando composer style-lint`: Check SCSS for standards/errors.
    - `lando composer style-fix`: Fix standard/errors in SCSS.
  - `lando config`: Displays the lando configuration
  - **`lando db-export [file]`: Exports database from a database service to a file**
  - **`lando db-import <file>`: Imports a dump file into a database service**
  - `lando destroy`: Destroys your app
  - `lando drupal`: Runs drupal console commands
  - `lando drush`: Runs drush commands has many subcommands including:
    - **`lando drush cr`: Clears drupal caches.**
    - `lando drush cim`: Imports configuration from the disk into your local site.
    - `lando drush cex`: Exports configuration from your local site to disk.
  - **`lando gulp`: Runs gulp commands - defaults to building the css in the primary theme.**
  - `lando info`: Prints info about your app
  - **`lando list`: Lists all running lando apps and containers**
  - `lando npm`: Runs npm commands
  - `lando php`: Runs php commands
  - `lando poweroff`: Spins down all lando related containers
  - **`lando pull`: Pull code, database and/or files from Pantheon - most commonly as `lando pull -c none -d live -f 
  none`** to get the latest database from pantheon's live site.
  - `lando push`: Push code, database and/or files to Pantheon
  - `lando rebuild`: Rebuilds your app from scratch, preserving data
  - `lando restart`: Restarts your app
  - `lando share`: Shares your local site publicly
  - `lando ssh`: Drops into a shell on a service, runs commands
  - **`lando start`: Starts your app**
  - **`lando stop`: Stops your app**
  - `lando switch`: Switch to a different multidev environment
  - **`lando terminus`: Runs terminus commands. Handy trick, you can use this to run terminus on any site not just your
   current site.**
  - `lando xdebug-off`: Disable xdebug for nginx.
  - `lando xdebug-on`: Enable xdebug for nginx.
  - `lando xdebug-profiler-off`: Disable xdebug profiler for nginx.
  - `lando xdebug-profiler-on`: Enable the xdebug profiler for nginx.

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

## Debugging

### Deploy Errors
While deploys are generally very reliable, there are various situations that can cause errors. In pretty much all of
those, your first step should be checking the failed build log in Circle CI for what the error is.

#### Build was canceled
Someone canceled the build/deploy through the CircleCI UI.

#### Too long with no output (exceeded 10m0s)
The most common error, however it is just a time out. Check the previous command run/output in the build log, to find
out what timed out and hopefully a reason. If you don't immediately see a reason, re-running the task may resolve it if
it was just a random failure or Pantheon was slow etc.

#### codeserver.dev.[numbers and dashes]@codeserver.dev.[numbers and dashes].drush.in's password:
This means that for some reason the defined SSH Key(s) for the project are not being accepted. Usually this is because
someone deleted the generated SSH key from their account. To fix this:
- Generate a new SSH key pair.
- [Add it to your Pantheon account](https://pantheon.io/docs/ssh-keys)
- Add it to Circle CI. Go to https://circleci.com/gh/Taoti/[your-project]/edit#ssh, and click `Add SSH Key`, then enter
`drush.in` for hostname and paste your SSH private key contents, then click `Add SSH Key`.
- If desired, remove the previous ssh key.

#### Client error: `GET https://api.github.com/`:
Full error message will be something like 
```
[error]  Client error: `GET https://api.github.com/repos/Taoti/earth-lab-cu/pulls?state=all` resulted in a `404 Not Found` response:
{"message":"Not Found","documentation_url":"https://developer.github.com/v3/pulls/#list-pull-requests"}
```
This error message is quite deceptive. It should actually be a 403 Forbidden usually. It means that the Github Token is not being accepted. To fix this:
- Generate a new Github Token
- Delete and Replace existing GITHUB_TOKEN environment variable in the project - go to https://circleci.com/gh/Taoti/[your-project]/edit#env-vars

### Lando Errors
If lando has any errors what so ever, the first step to debugging is:
```bash
lando stop
lando start
```
If whatever error is still occuring, then try running:
```bash
lando restart
```

Examples of errors that this will fix include:
```bash
$ lando gulp
gulp: 1: gulp: gulp: not found
$ lando npm install
npm: 1: npm: npm: not found
```

### PHP Errors
You can use all your your normal debugging methodologies. Lando tooling has been provided for easy XDebug usage as well.

#### XDebug
XDebug is bad for performance, but amazing for debugging. Therefor, by default, XDebug is turned off. To turn it on run
`lando xdebug-on`, when done, run `lando xdebug-off`. Configuration necessary for your IDE may vary based on what IDE
and OS you are using.

##### PHPStorm on Windows
Fairly straight forward, but can be persnickity about certain files in `USER_HOME$/.lando/config/pantheon` if not setup
correctly. Here is a functional snippet out of `.idea/workspace.xml` which you can use to easily setup with. (replace
strings in square brackets as necessary).

```xml
<?xml version="1.0" encoding="UTF-8"?>
<project version="4">
[snip]
  <component name="PhpDebugGeneral" notify_if_session_was_finished_without_being_paused="false" />
  <component name="PhpServers">
    <servers>
      <server host="localhost" id="20abe6a8-f1d0-447f-b950-62df77becb66" name="localhost" use_path_mappings="true">
        <path_mappings>
          <mapping local-root="$USER_HOME$/.lando/config/pantheon" remote-root="/srv/includes" />
          <mapping local-root="$PROJECT_DIR$" remote-root="/app" />
        </path_mappings>
      </server>
    </servers>
  </component>
  <component name="PhpWebServerValidation" path_to_validation_script="C:\[path to project]\web" selected_validation_type="LOCAL" web_path_to_validation_script="https://[project-name]earth-lab-cu.lndo.site" />
  <component name="PhpWorkspaceProjectConfiguration">
    <include_path>
      [Default generate include paths which vary per project]
      <path value="$USER_HOME$/.lando/config/pantheon" />
    </include_path>
  </component>
[snip]
</project>
```
