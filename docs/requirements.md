# REQUIREMENTS
Due to using Lando, developing sites with the Taoti Drupal 8 Composer setup has very minimal requirements. You don't
need an AMP stack or to install Node or most other tools. 

Much of the development processes utilize the command line, so please ensure you are familiar with the command line for
your operating system. If you are not comfortable with the command line, a good introduction - including many of the
tools that we are using - can be found in Tara King's excellent session [Command Line Basics](
https://drupal.tv/external-video/2018-10-27/command-line-basics-site-builders).

If you will be setting up new sites as well as developing existing sites, see the [New Site Requirements](
requirements-new-site.md) as there are a few additional requirements there.

## Operating System:
Any modern MAC OS, Linux or Windows variant should work. For Windows, you will need Windows Professional not Home
edition for Docker to run.

## Getting Lando set up

### Install Lando
Follow the [Lando documentation](https://docs.devwithlando.io/installation/system-requirements.html), Again, choose the
OS that is applicable for your local environment. This guide is tested with "v3.0.0-rc.20" but latest stable release
should be dandy. On some OS'es (such as Windows) Lando bundles Docker and will walk you through that installation, if it
doesn't for your OS, you will need to install Docker as well.

### Install Docker

See [Install Documentation](https://docs.docker.com/install/) and select the OS applicable to your local environment
from the sidebar.

> Note: If you don't know what you're doing with docker, and you've tinkered with it (have it already installed locally),
it is _strongly_ suggested that you completely uninstall it before moving on. At the very least you should be able to
run `$ docker --help` and see meaningful output.

### Trust the lndo.site Ceritificate Authority
Lando uses built in SSL certificates to provide local hosts with under HTTPS for more accurate comparison to your live
sites. To trust them and avoid browser warnings, **_after_** initializing your first Lando environment, follow the 
[Lando Security Instructions](https://docs.devwithlando.io/config/security.html) for your OS.

> Note: Mozilla Firefox has an [additional configuration change](
https://docs.devwithlando.io/config/security.html#firefox-maintains-its-own-certificate-store) needed, so if you are
developing or testing in that browser ensure you perform that configuration step as well.
