# Standards
Standards. A lot of things can be covered by invoking standards - same as best practices - but without a agreement on
what those are they are quite useless. Agency life both makes standards sometimes challenging - 🕘 is always a concern,
high turnover, inherited/old sites, lack of documentation etc - but those same reasons makes standards critical. To make
standards work a few things are needed - documentation, and automation. If standards are a lot of work, it won't happen.

**Standards do not fix bugs** code that doesn't follow standards is not necessarily any better or worse than that which
 does. Standards do make things easier to read - and in some cases can prevent bugs - but the primary benefit is on that
 readability. Also even when following standards, documentation is important - it is much better to document your code
 and be a bit non-standard. The best standards are automated so you don't have to think about it in most cases. 

## PHP
Our PHP standards are the same as the
[Drupal Core Coding Standards](https://www.drupal.org/docs/develop/standards/coding-standards). We don't really have any
commit or patch standards - so feel free to fix standards in unrelated issues (although better as it's own commit).
To ease compliance, the Drupal Coder standard is used with PHPCS to provide commands including:

- `lando composer code-sniff`: (aka `composer code-sniff` if you aren't using lando). Check and report standards
compliance.
- `lando composer code-fix`: (aka `composer code-fix` if you aren't using lando). Check and fix all standards errors
that are programmatically fixable and report numbers.

Most IDEs can also integrate with PHPCS to provide as you type feedback. Instructions differ per IDE so best to look it
up for your IDE if interested. Almost all PHP standards are 100% automaticly fixable - the main exception being some
comments, it can't know what your *intent* behind code is.

## SASS/CSS
The Drupal Core standards are for CSS rather than SASS, so not even if we wanted to not all things could or would apply.
However, the Drupal core standards are quite a bit more strict than we need in this case. So we are using the same
tooling as core - stylelint - and in fact use the Core standards with some additions and a bunch of rules disabled. 

Provided via `lando composer style-lint`/`lando composer style-fix` - Check/auto-fix all standards errors.

There is poor documentation of the core CSS standards and we diverge from it quite a bit, so a summary:

- All files should have trailing new lines.
- _Avoid_ have ID selectors.
- _Avoid_ Selectors deeper than 5 levels (excluding psuedo-classes).
- _Avoid_ use `!important`.
- No trailing spaces.


Of course sometimes some things are unavoidable, so [ignore them](https://stylelint.io/user-guide/ignore-code). For
example:
```css
#someid { /* stylelint-disable-line */
  color: green;
}
```

## Requirements
Lando is configured to provide all requirements. However, not everyone wants to use Lando - and that's fine - but for
those people they will have to manually provide some of the requirements.

- For **PHPCS**: The PHPCS Binary is included in the composer development dependencies; all you need to do is ensure
working PHP and composer - which you have to have if you aren't using Lando.
- For **Stylelint**: This one is a bit more complicated and will require some external installs beyond the minimum
required to run the site. As NPM is required for basic site building (SASS to CSS) that is assumed to be available.
You will need to isntall stylint allong with some plugins and the standard stylelint config.
```bash
npm install --global stylelint stylelint-no-browser-hacks stylelint-config-standard stylelint-order
```

## Automation
Recent sites that have Continuous Integration enabled will automatically check PHP and stylelint standards and complain
if they don't pass. Which lets you know to fix it, through Composer (and Lando) manual running of both checkers and the
automatic fixers is doable. However, that is an extra thing to remember. Another option is also provided... git hooks.

Git hooks perform some automated actions on git events. We provide a pair of git commit hooks to automatically run the
automatic code standards fixers on any code being committed. To install these, run `composer git-hooks -- lando`
or `composer git-hooks` depending on whether they should run within Lando or not. 

Automatic fixers *cannot* fix all problems, but by using them, you can focus on the more important ones that require
human attention.
