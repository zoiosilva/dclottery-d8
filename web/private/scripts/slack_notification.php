<?php

/**
 * Get secrets from secrets file.
 *
 * @param array $requiredKeys  List of keys in secrets file that must exist.
 */
function _get_secrets($requiredKeys, $defaults)
{
  $secretsFile = $_SERVER['HOME'] . '/code/secrets.json';
  if (!file_exists($secretsFile)) {
    die('No secrets file found. Aborting!');
  }
  $secretsContents = file_get_contents($secretsFile);
  $secrets = json_decode($secretsContents, 1);
  if ($secrets == FALSE) {
    die('Could not parse json in secrets file. Aborting!');
  }
  $secrets += $defaults;
  $missing = array_diff($requiredKeys, array_keys($secrets));
  if (!empty($missing)) {
    die('Missing required keys in json secrets file: ' . implode(',', $missing) . '. Aborting!');
  }
  return $secrets;
}

/**
 * Send a notification to slack
 *
 * @param array $attachment
 *   The post to publish to Slack.
 *   Structure should be:
 *   [
 *     'fallback' => 'a fallback text only message',
 *     'color' => HEXCODE OR 'good'/'warning'/'danger',
 *     'fields' => [
 *       [
 *         'value' => 'richtext message in slack format',
 *         'short' => TRUE|FALSE,
 *       ],
 *     ],
 *   ].
 *   @see https://api.slack.com/messaging/composing/layouts#attachments
 *   @see https://api.slack.com/docs/messages/builder
 * @param null $slack_url
 *   (optional) Slack url to post to, falls back to url from secrets.json.
 * @param null $channel
 *   (optional) channel to post to, falls back to #dev or secrets.json.
 * @param null $username
 *   (optional) Username to post as, falls back to Pantheon or secrets.json.
 * @param null $alwaysShowText
 *   (optional) Whether to always show text, falls back to FALSE or secrets.json.
 */
function _slack_notification(array $attachment, $slack_url = NULL, $channel = NULL, $username = NULL, $alwaysShowText = NULL) {
  $secrets = _get_secrets(['slack_url'], [
    'slack_username' => 'Pantheon',
    'channel' => '#dev',
    'always_show_text' => FALSE,
  ]);
  $slack_url = $slack_url ?? $secrets['slack_url'];
  $channel = $channel ?? $secrets['channel'];
  $username = $username ?? $secrets['slack_username'];
  $alwaysShowText = $alwaysShowText ?? $secrets['always_show_text'];

  $post = [
    'username' => $username,
    'channel' => $channel,
    'icon_emoji' => ':pantheon:',
    'attachments' => [$attachment]
  ];
  if ($alwaysShowText) {
    $post['text'] = $attachment['fallback'] ?? 'No text available';
  }
  $payload = json_encode($post);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $slack_url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  // Watch for messages with `terminus workflows watch --site=SITENAME`
  print("\n==== Posting to Slack ====\n");
  $result = curl_exec($ch);
  print("RESULT: $result");
  // $payload_pretty = json_encode($post,JSON_PRETTY_PRINT); // Uncomment to debug JSON
  // print("JSON: $payload_pretty"); // Uncomment to Debug JSON
  print("\n===== Post Complete! =====\n");
  curl_close($ch);
}
