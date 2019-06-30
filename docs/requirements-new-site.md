# REQUIREMENTS FOR SITE CREATORS
In addition to the minimal requirements for developing with Lando, creating new sites on Pantheon with full CI requires
a few more tools available locally. Note that Terminus itself is **unsupported** on Windows so your mileage on that may
vary - at minimum you would also need to install a sed replacement, since sed isn't available by default. 

## Install PHP
[PHP](https://www.php.net/) is necessary for running composer and terminus locally. You should have PHP 7.2 or higher.

* **MAC OS**: Mac comes with PHP by default. However, it may be outdated. If so, see [official install instructions](
https://www.php.net/manual/en/install.macosx.packages.php)
* **Linux**: Most nix variants supply PHP from their package managers.

## Install Composer
See the [Composer documenation](https://getcomposer.org/download/), which include an installation script. On Unix
environments, replacing the 3rd line with `php composer-setup.php --install-dir=bin --filename=composer` is beneficial.

Depending on system speed, setting composer timeout higher than the default may prevent problems. If you run into
timeouts or if you suspect your system is slow, you can run:
```bash
composer config --global process-timeout 900
```
 

## Install Terminus
See the [Terminus install documentation](https://pantheon.io/docs/terminus/install/). Use version 2.x.

TLDR:
```
curl -O https://raw.githubusercontent.com/pantheon-systems/terminus-installer/master/builds/installer.phar && php installer.phar install
```

### Install Terminus Build Tools Plugin
You will also need the [build tools plugin](https://github.com/pantheon-systems/terminus-build-tools-plugin/). Until 
[PR #207](https://github.com/pantheon-systems/terminus-build-tools-plugin/pull/207) is merged, you will need to use Nick
Wilde's [fork/branch](https://github.com/NickWilde1990/terminus-build-tools-plugin/tree/create-project-build-assets).

```
mkdir -p ~/.terminus/plugins
git clone git@github.com:NickWilde1990/terminus-build-tools-plugin.git --branch create-project-build-assets ~/.terminus/plugins/terminus-build-tools-plugin
cd ~/.terminus/plugins/terminus-build-tools-plugin
composer install
```
