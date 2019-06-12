# Taoti Drupal 8 Baseline
This repository provides a detailed guide to setting up a local development environment that utilizes a Composer based workflow with Drupal 8.

Please ensure that you follow the directions outlined below to install and configure the necessary requirements for this baseline.

Below is a list of requirements that will ensure you get the most out of the training.

## Requirements
- Administrative rights to install and configure various applications
- Lando (Docker based setup)
- Terminal
- Composer
- Node & NPM
- Gulp
- Git

### Administrative rights
You will need to ensure that you have administrative rights to install, configure or manage file permissions required by the list of tools outlined above.  If you do not have administrative rights, in the case of using a work laptop, then please have your company install the following items for you.


### Docker / Lando
To eliminate the need for various setups that may involve different AMP (Apache/MySQL/PHP) stacks we recommend using a Container based setup using Docker and Lando. You can verify the system requirements for Lando by navigating to the [System Requirements](https://docs.devwithlando.io/installation/system-requirements.html) page and following the directions for your operating system.

### Terminal
The terminal is an interface in which we can execute text based commands.  It can be much faster to complete some tasks using a Terminal than with graphical applications and menus. The remaining requirements will be mostly ran from a Terminal using a series of command line prompts.  Take a moment to ensure that you have a Terminal (MAC) or Command Prompt (Windows) available to use.

We will be using the terminal window to work with tools such as `Composer`, `NPM`, and `Grunt` throughout the training.  It is important to be comfortable using the command line as it should be part of any daily Front End development workflow.

### Composer
Composer (https://getcomposer.org/) is a dependency manager for PHP that allows us to perform a multitude of tasks; everything from creating a Drupal project to declaring libraries and even installing contributed modules. The advantage of using Composer is that it allows us to quickly install and update dependencies by simply running a few commands from a terminal window.

`Lando` will allow us to run these commands without the need to physically install `Composer` on our computer or laptop.  We will revisit the various `Composer` commands that will be used during the training.

### Node & NPM
[Node](https://nodejs.org/en/) is a cross platform runtime environment for creating server side and networking applications. JavaScript running outside the browser. [NPM](https://www.npmjs.com/) is the package manager for JavaScript used to install, share, and distribute code and is used to manage dependencies in projects.

> We will be using NPM to manage dependencies when working with themes in Drupal 8.

`Lando` will allow us to run these commands without the need to physically install `NPM` on our computer or laptop.  We will revisit the various `NPM` commands that will be used during the training.

### Gulp
[Gulp](https://gulpjs.com/) is a JavaScript task runner that allows us to perform repetitive tasks like minification, compilation, unit testing, linting and more. We use `Gulp` to compile Sass, Pattern Lab and watch for file changes during development.

`Lando` will allow us to run these commands without the need to physically install `Gulp` on our computer or laptop.  We will revisit the various `Gulp` commands that will be used during the training.

## Using the training files and configuring Drupal

### Downloading the training files
Now that we have all the necessary requirements out of the way we can proceed to downloading a copy of the training files located within the Master branch.

Begin by locating the green `Clone or download` drop-down button and choosing **Download ZIP**.  Locate the zipped file named `components-master.zip` and extract it's contents. Make sure to expand the `components-master.zip` folder and rename the `components-master` folder to `components`.  For sake of demonstration, I will be copying this folder to a directory called **Sandbox**.

### Lando

To eliminate the need for various setups that may involve different **AMP** (Apache/MySQL/PHP) stacks and consider yousrself an advanced user then you can choose to use `Lando` a Docker based development environment to work with PHP, MySQL and Drupal.  You can download and install Lando for Windows, MACOS and Linux by navigating to the [download](https://docs.devwithlando.io/installation/installing.html) page and following the install prompts for your operating system.  Make sure to check the [System Requirements](https://docs.devwithlando.io/installation/system-requirements.html) prior to installing Lando.

Once completed we will revisit how to use Lando to import a Drupal 8 website as well as how to start a server, run composer, drush and import the initial database snapshot that will be used throughout the training.


### Setup
The initial setup of Lando requires a .lando configuration file which I have already provided in the root of the project.  In order to initialize the Docker containers needed by Lando we will need to execure the following commands within the terminal window.

```
  cd Sandbox/components
  lando start
```

Once Lando finishes spinning up the containers we need to scaffold up our Drupal 8 instance by running the composer install command within the terminal window.  However, Lando requires the lando prefix in order to execute other tools so enter the following command within the terminal window.

```
  lando composer install
```

With our Drupal 8 instance now configured we need to import the database.  Located within the db folder is a SQL dump we can use to import into the MySQL container using the `db-import` command.


```
  cd db
  lando db-import components.sql.gz
```

Our database is now imported and all that is left to do is to configure our theme and scaffold up our Pattern Lab instance and compile the Sass files need for our theme.  We can easily do that by entering the following command within the terminal window..

```
  cd ..
  cd web/themes/copycat
  lando npm install
  lando gulp watch
```

Now that our theme is no installed and configured we can preview our Drupal 8 website by either selecting the URL within the terminal window or by opening a browser and navigating to http://components.lndo.site/user and logging in with the following credentials:

- username: **admin**
- password: **admin**

## Congratulations
We now have a Drupal 8 project titled Pacific Whale Conservancy that we will be using throughout the remaining training. This Drupal 8 instance is configured with the latest best practices in mind for site building. This includes use of the Media module, Paragraphs, various Twig modules and the Component libraries modules.

This training does not cover site building but we will briefly discuss various decision made when implementing a component-based theme using Twig and Pattern Lab.
