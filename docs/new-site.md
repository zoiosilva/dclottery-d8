# CREATE A NEW TAOTI DRUPAL 8 COMPOSER BASED SITE

## Initialize Local Site Repository
- Clone the [base repository](https://github.com/Taoti/drupal8) to your computer to a new folder. Use the clone command
to place your new site in a folder to match the name you're about to use for \[NEW-MACHINE-SITE-NAME\] below.
```bash
git clone https://github.com/Taoti/drupal8.git my-new-site
```

- Remove the git origin remote, this is important to do because the next command we run is going to establish the git
remote and do everything else for us. 
```bash
git remote remove origin
```

## Initializing Your New Site

- Run the following command to get everything started with GitHub, CircleCI and Pantheon:
Please note that "!" (exclamation points) don't work in the `--admin-password field`. `[NEW-SITE-MACHINE-NAME]` must be 
lower case, hyphenated and cannot contain symbols.
```bash
terminus build:project:create --team="Taoti Creative" --admin-email="taotiadmin@taoti.com" --admin-password="[REDACTED]" --ci=circleci --git=github ./ [NEW-SITE-MACHINE-NAME] --preserve-local-repository
```

> Note: if you get a drush permissions error while that is attempting to run, you likely need [drush permissions patch](
https://github.com/NickWilde1990/terminus-build-tools-plugin/commit/1ed3bfc4d52bc0eafc6b93da8b9cbb4308e28eca). If so,
run:
```bash
cd ~/.terminus/plugins/terminus-build-tools-plugin
curl https://github.com/NickWilde1990/terminus-build-tools-plugin/commit/1ed3bfc4d52bc0eafc6b93da8b9cbb4308e28eca.patch > permissions.patch
git apply permissions.patch
```

## Post Creation steps

- Edit `.lando.yml` and change the site name to your new site name, and update config values to match your new site's
pantheon instance.

- Update the title of the `README.md` to show that this is a specific site, not the base code.
  
- Initialize your local Lando instance for this site - for more details see the [primary readme](../README.md) - but use
the local database in `db/drupal8.sql.gz` and push that to Pantheon as it has some config changes etc that we want to
use rather than the default Pantheon installed database. 

```bash
lando start
lando db-import db/drupal8.sql.gz
lando drush updb -y
lando drush cex -y
git add -A
git commit -m "updated config"
lando push --files=none --code=none --database=dev
git push --set-upstream origin master
```

### Transition github repository created on your user to Taoti
When you use this method, the Github repository that gets set up will be owned by your Github user. We need to transfer
it to Taoti to allow other developers to work on it. Read the Github documentation on [transfering a repository](
https://help.github.com/en/articles/transferring-a-repository)

> TLDR: Go to the new Github repo > settings > (scroll to bottom) Transfer Repository
