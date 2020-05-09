# COMPOSER BASED CUSTOM UPSTREAM
The custom upstream functionality of Pantheon is quite useful. However it does not/cannot easily work with CI
functionality.
All is not lost though, the powers of git let us do something quite similar - albeit with CLI instead of GUI.

## Setting up a custom CI upstream
- Create a new empty repo in the Taoti workspace on Github (for example: `drupal8-upstream-CUSTOMPROJECTGROUP`).
- Check out the base [Taoti Drupal 8 Repository](https://github.com/taoti/drupal8) to a new folder named the same as
your new upstream name.
- In your clone run:
```bash
git remote remove master
git remote add master [CLONE URL FOR YOUR NEW REPO]
git push
```
Deleting the `composer.lock` from the upstream will often reduce merge conflicts, but depending on usage may not be
desired.

Also, variable depending on needs, but frequently you'll want the custom theme included in the upstream. In that case,
cherry picking from the first created site is usually the best bet (see below for instructions).

## Setting up new site using the upstream
- Follow the [normal new site instructions](new-site.md) - except use the custom upstream you created earlier as the
base rather than the normal `Taoti/Drupal8` repo.

## Using the upstream

### Pulling changes the upstream to a child site
```bash
## Only have to add the upstream once per clone.
git remote add upstream [CLONE URL FOR THE UPSTREAM]
## Have to fetch upstream everytime.
git fetch upstream
## Depending on workflow create a branch or not (ie branch if doing a PR workflow).
git checkout -b "Updates"
git merge upstream/master
## Resolve any merge conflicts. Most likely problem files include lock files.
git push
## Your normal PR/deploy/test workflow.
```

### Pushing changes to the upstream
You can do any of your normal update methodologies to the upstream.
Depending on change, you may find it best to copy and paste or to cherrypick changes. One of the main benefits of cherry
picking is that then you won't have merge conflicts if you later merge the upstream to the same site you pulled that
change from.

Rough cherrypick guide:
- add the child site as an extra remote
- fetch the child remote
- cherrypick commits as desired.
- push.
