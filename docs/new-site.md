# CREATE A NEW TAOTI DRUPAL 8 COMPOSER BASED SITE
Note that creating a new site has more [requirements](requirements-new-site.md) than developing an existing site.

## Initialize Local Site Repository
- Clone the [base repository](https://github.com/Taoti/drupal8) to your computer to a new folder. Use the clone command
to place your new site in a folder to match the name you're about to use for \[NEW-MACHINE-SITE-NAME\] below.
```bash
git clone https://github.com/Taoti/drupal8.git my-new-site
```

## Initializing Your New Site

- Run the following command to get everything started with GitHub, CircleCI and Pantheon:
```bash
cd my-new-site
composer setup-new-site
```
and follow prompts.

> Note: if you get a drush permissions error while that is attempting to run, you likely need [drush permissions patch](
https://github.com/NickWilde1990/terminus-build-tools-plugin/commit/1ed3bfc4d52bc0eafc6b93da8b9cbb4308e28eca). If so,
run:
```bash
cd ~/.terminus/plugins/terminus-build-tools-plugin
curl https://github.com/NickWilde1990/terminus-build-tools-plugin/commit/1ed3bfc4d52bc0eafc6b93da8b9cbb4308e28eca.patch > permissions.patch
git apply permissions.patch
```


## Post Creation steps

- Edit `.lando.yml` and change the site ID to your new site ID from Pantheon (long UUID type string)

- Update the title of the `README.md` to show that this is a specific site, not the base code.
  
- Initialize your local Lando instance for this site - for more details see the [primary readme](../README.md) - you
can pull the installed database from pantheon via:
```bash
lando pull --database=dev --files=none --code=none
```

- Make the repository private:
    - go to your new repository (which will have an url like `https://github.com/Taoti/NEW-SITE-MACHINE-NAME`).
    - Click the `settings` tab near the top.
    - Scroll alllll the way down, and click `Make Private` in the Danger Zone and follow prompts.
