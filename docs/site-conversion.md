# Converting a Site from D8 to this repository's structure

- Clone this repository into a clean directory
- Create a set of files that provide the contrib module differences between the D8 repository and this repository
  - `$ ls -1a ./path/to/this-repo/../web/modules/contrib > composer-mods.txt`
  - `$ ls -1a ./path/to/old-repo/../modules/contrib > old-mods.txt`
- Evaluate the differences between them
  - `$ diff composer-mods.txt old-mods.txt`
 - Remove the modules from the `composer.json` file for the ones that don't exist in the source repository
 - Add the modules that do exist in the source repository utilizing `composer require drupal/[module_name]`
   - You can nest several `drupal/[module_name]` in the same `composer require` statement
 
 
