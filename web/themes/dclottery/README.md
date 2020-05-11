# MIMIC - Taoti D8 Starter Theme #

## Setup ##
Search across all files in this directory for `SETUP:` for helpful placeholders to get setup quickly (adding fonts, favicons, etc).

Setup styling variables in `sass/_settings.scss`.

Read the `readme.md` files inside of `sass/` sub-directories for more guidance.


## Compiling SASS ##

`npm install` to setup the SASS stack for theming. If this fails or gives you problems, try deleting the `package-lock.json` file first, then running `npm install` again. 

Sometimes the package configuration locked by the last person to commit the lock file does not work for different platforms. It's generally OK to commit the package-lock.json file regardless.

`gulp css` to compile SASS.

`gulp watch` to compile SASS as you edit.

If you have the `livereload` plugin installed in your browser, you can use `gulp watch-reload` to watch compile SASS and immediately update it in the browser without a page refresh (make sure to enable the livereload plugin after you start `gulp watch-reload`, it's usually a little "refesh" icon with a dot filled-in when it is activated)
