<?php

include __DIR__ . '/slack_notification.php';

// Get the committer, hash, and message for the most recent commit.
$committer = `git log -1 --pretty=%cn`;
if (strstr($committer, 'CI Bot') !== FALSE || $_ENV['PANTHEON_ENVIRONMENT'] !== 'dev') {
  return;
}

$email = `git log -1 --pretty=%ce`;
$message = `git log -1 --pretty=%B`;

// Also break early if this is a site creation commit.
if (strstr($message, 'Build assets for dev.')) {
  return;
}
$hash = `git log -1 --pretty=%h`;


$text = "WARNING!! This project is configured to use continuous integration. A deploy has just been made to {$_ENV['PANTHEON_ENVIRONMENT']} that does not appear to have come from the CI. The next deploy via the CI may overwrite this without warning. ARE YOU SURE????? See https://dashboard.pantheon.io/sites/{$_ENV['PANTHEON_SITE']}#{$_ENV['PANTHEON_ENVIRONMENT']}/deploys";

$attachment = [
  'fallback' => $text,
  'color' => 'danger',
  'fields' => [
    [
      'value' => "WARNING!! This project is configured to use continuous integration. A deploy has just been made to {$_ENV['PANTHEON_ENVIRONMENT']} that does not appear to have come from the CI.
      The next deploy via the CI may overwrite this without warning. ARE YOU SURE?????
      Commit by: {$committer}.
      <http://{$_ENV['PANTHEON_ENVIRONMENT']}-{$_ENV['PANTHEON_SITE_NAME']}.pantheonsite.io|{$_ENV['PANTHEON_SITE_NAME']}.{$_ENV['PANTHEON_ENVIRONMENT']}>
      <https://dashboard.pantheon.io/sites/{$_ENV['PANTHEON_SITE']}#{$_ENV['PANTHEON_ENVIRONMENT']}/deploys|:pantheon:dashboard>",
      'short' => 'false'
    ],
  ],
];


_slack_notification($attachment);
