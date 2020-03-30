# Converting a Site from D8 to this repository's structure
There are many exact structures that a D8 site can be in. These instructions should match most cases but there are
certainly outliers that will be more complicated and or have differences that make it difficult to convert.

## Getting started
- Clone the target site's repository into a clean directory

## Dealing with non-composer managed sites
If this isn't a composer managed site already, you will need to move it to being composer managed.

- Copy the basic `composer.json` from this repo to your site repo.

- Create a set of files that provide the contrib module differences between the D8 repository and this repository

  - `$ ls -1a ./path/to/this-repo/../web/modules/contrib > composer-mods.txt`
  - `$ ls -1a ./path/to/old-repo/../modules/contrib > old-mods.txt`

- Evaluate the differences between them
  - `$ diff composer-mods.txt old-mods.txt`
  - Remove the modules from the `composer.json` file for the ones that don't exist in the source repository

- Add the modules that do exist in the source repository utilizing `composer require drupal/[module_name]`
  - You can nest several `drupal/[module_name]` in the same `composer require` statement

## Cleaning the repository
The repository almost certainly has a lot of files committed that shouldn't be. So we need to adjust `.gitignore` files
and perform other cleanup tasks.

- Copy the `.gitignore` from this repo's root over the `.gitignore` in your new site's root. If it had customizations
you may need to do a merge but that will require analysis/reviewing the specific changes to determine if they are
desirable.

- Do the same with the `.gitignore` in the custom theme directory copying the one from this repo's sparkle_subtheme.
This will likely need some adjustment to match the current theme's structure if it is an older site.

- run `git ls-files -i --exclude-standard` to list all files that are now ignored that were previously committed. For
each of them confirm they should be removed and remove them with `git rm --cached -r PATHS[]`. You can do whole folders
with the `-r` flag. A good start for most repositories is `git rm --cached vendor web/modules/contrib web/core`

## Other Code Updates
- Copy the latest `.ci` and `.circleci` folders from this repository to your new repo.

- Adjust paths in `.circleci/config.yml` to match the correct theme as needed.

- Update `composer.json` to ensure that at least the same scripts as are in the composer.json are available in the
new repository.

- Update `composer.json` (and lock) to ensure that the dev dependencies include at minimum the dev dependencies from
this repo.

## Non-Pantheon Site (or to create a new Pantheon Site)

- Follow normal [new site procedures](new-site.md) from here on out including tailoring the lando file, creating the
project with the terminus command and so forth.

## Updating an existing Pantheon Site
- Copy `.lando.base.yml` and `.lando.yml` to the new repository, updating `.lando.yml` to match the current site's
pantheon ID/name.

- Run `git remote remove origin`

- Create a new repository on Github in the Taoti namespace - and leave it as a blank repo.

- Add the new remote `git remote add origin NEWORIGIN`

- Push to Github

- Adjust repository settings on Github, under settings -&gt; Collaboration &amp; Teams, add `Developers` team with R&W
access.

- Go to [CircleCI](https://circleci.com/add-projects/gh/Taoti) and add as a Linux Project.

- Leave all configurations defaults and set to build now (it will fail)

- Go to settings
    - Under `SSH Permissions` Add a SSH _private_ key with access to the Pantheon site (you can create a new one or use
    an existing one). Use `drush.in` as the hostname.
   - The required SSH private key is require it to be in the RSA format - https://stackoverflow.com/questions/54994641/openssh-private-key-to-rsa-private-key
    - Under  Environment Variables add:
        - TERMINUS_SITE: The pantheon site machine name (for example `dcpcsb-d8`).
        - TERMINUS_TOKEN: A valid terminus token with current access.
        - GITHUB_TOKEN: A valid [Github token](https://github.com/settings/tokens)  with at least `repo` permissions.

- Rebuild and fix any standards errors in the custom code when possible.
 
