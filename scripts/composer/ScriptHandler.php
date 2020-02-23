<?php

/**
 * @file
 * Contains \DrupalProject\composer\ScriptHandler.
 */

namespace DrupalProject\composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ScriptHandler
{

  protected static function getDrupalRoot($project_root)
  {
    return $project_root .  '/web';
  }

  public static function createRequiredFiles(Event $event)
  {
    $fs = new Filesystem();
    $root = static::getDrupalRoot(getcwd());

    $dirs = [
      'modules',
      'profiles',
      'themes',
    ];

    // Required for unit testing
    foreach ($dirs as $dir) {
      if (!$fs->exists($root . '/'. $dir)) {
        $fs->mkdir($root . '/'. $dir);
        $fs->touch($root . '/'. $dir . '/.gitkeep');
      }
    }

    // Create the files directory with chmod 0777
    if (!$fs->exists($root . '/sites/default/files')) {
      $oldmask = umask(0);
      $fs->mkdir($root . '/sites/default/files', 0777);
      umask($oldmask);
      $event->getIO()->write("Create a sites/default/files directory with chmod 0777");
    }
  }

  // This is called by the QuickSilver deploy hook to convert from
  // a 'lean' repository to a 'fat' repository. This should only be
  // called when using this repository as a custom upstream, and
  // updating it with `terminus composer <site>.<env> update`. This
  // is not used in the GitHub PR workflow.
  public static function prepareForPantheon()
  {
    // Get rid of any .git directories that Composer may have added.
    // n.b. Ideally, there are none of these, as removing them may
    // impair Composer's ability to update them later. However, leaving
    // them in place prevents us from pushing to Pantheon.
    $dirsToDelete = [];
    $finder = new Finder();
    foreach (
      $finder
        ->directories()
        ->in(getcwd())
        ->ignoreDotFiles(false)
        ->ignoreVCS(false)
        ->depth('> 0')
        ->name('.git')
      as $dir) {
      $dirsToDelete[] = $dir;
    }
    $fs = new Filesystem();
    $fs->remove($dirsToDelete);

    // Fix up .gitignore: remove everything above the "::: cut :::" line
    $gitignoreFile = getcwd() . '/.gitignore';
    $gitignoreContents = file_get_contents($gitignoreFile);
    $gitignoreContents = preg_replace('/.*::: cut :::*/s', '', $gitignoreContents);
    file_put_contents($gitignoreFile, $gitignoreContents);

    // Fix up .gitignore: remove everything above the "::: cut :::" line
    $gitignoreFile = getcwd() . '/web/themes/THEME_NAME/.gitignore';
    $gitignoreContents = file_get_contents($gitignoreFile);
    $gitignoreContents = preg_replace('/.*::: cut :::*/s', '', $gitignoreContents);
    file_put_contents($gitignoreFile, $gitignoreContents);
  }

  public static function setupTheme(Event $event) {
    $io = $event->getIO();

    $theme = $io->ask('Provide new theme name: ');

    $web = getcwd() . '/web';
    $theme_dir = "{$web}/themes/{$theme}";
    $io->write('Checking out mimic base theme');

    // User running this may be set up for SSH or HTTPS so try both.
    exec("git clone https://github.com/Taoti/mimic.git  {$theme_dir}", $output, $status);
    if ($status) {
      exec("git clone git@github.com:Taoti/mimic.git  {$theme_dir}", $output, $status);
    }

    $io->write("Setting up theme {$theme}");
    $fs = new Filesystem();
    $fs->remove("{$theme_dir}/.git");
    $fs->rename("{$theme_dir}/mimic.info.yml", "{$theme_dir}/{$theme}.info.yml");
    $fs->rename("{$theme_dir}/mimic.libraries.yml", "{$theme_dir}/{$theme}.libraries.yml");
    $files = [
      '.circleci/config.yml',
      '.lando.base.yml',
      'composer.json',
      "{$theme_dir}/{$theme}.info.yml",
      "{$theme_dir}/{$theme}.libraries.yml",
      "{$theme_dir}/templates/paragraphs/paragraph--accordion.html.twig",
    ];

    foreach ($files as $filename) {
      $file = file_get_contents($filename);
      file_put_contents($filename, preg_replace("/THEME_NAME|mimic/", $theme, $file));
    }
    $filename = 'scripts/composer/ScriptHandler.php';
    $file = file_get_contents($filename);

    // Funny syntax is necessary since we don't want to replace the line itself.
    file_put_contents($filename, str_replace("/THEME_NAME/" . ".gitignore", "/{$theme}/.gitignore", $file));
    exec("cd {$theme_dir} && npm install");
  }

  public static function setupSite(Event $event) {
    self::setupTheme($event);
    $io = $event->getIO();
    $site = $io->ask('Provide new site name (for example: ifa-d8): ');
    exec('git remote remove origin');
    exec('terminus build:project:create --pantheon-site="'. $site . '" --team="Taoti Creative" --org="Taoti" --admin-email="taotiadmin@taoti.com" --admin-password="Taoti1996" --ci=circleci --git=github ./ '. $site . ' --preserve-local-repository');
    file_put_contents('.lando.yml', "
name: {$site}
config:
  site: {$site}
#  id: PANTHEONSITEID
    ");
  }

  public static function enableGitHooks(Event $event) {
    if (!ScriptHandler::commandExists('sh')) {
      echo "sh MUST be in your path for these git hooks to work.";
      exit(1);
    }
    $args = $event->getArguments();
    copy('scripts/githooks/post-commit', '.git/hooks/post-commit');
    $pre = 'scripts/githooks/pre-commit';
    $msg = "Git hooks set up for this project to run automatic standards fixes. ";
    if ($args[0] === 'lando') {
      $pre .= '-lando';
      $msg .= "Running via Lando.";
    }
    else {
      $msg .= "Running in your local environment. See documentation if necessary to confirm requirements.";
    }
    copy ($pre, '.git/hooks/pre-commit');
    echo $msg;
  }

  public static function commandExists($command) {
    $test = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? "where" : "which";
    return is_executable(trim(shell_exec("$test $command")));

  }
}
