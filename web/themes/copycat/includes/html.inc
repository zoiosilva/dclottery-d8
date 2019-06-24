<?php

/**
 * @file
 * HTML template functions.
 */

/**
 * Implements hook_preprocess_html().
 */
function copycat_preprocess_html(&$vars, $hook) {
  // Add x-ua-compatible meta tag.
  $vars['page']['#attached']['html_head'][] = [
    [
      '#tag' => 'meta',
      '#attributes' => [
        'http-equiv' => 'x-ua-compatible',
        'content' => 'ie=edge',
      ],
    ],
    'x_ua_compatible',
  ];
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function copycat_preprocess_maintenance_page(&$vars, $hook) {
  // While markup for normal pages is split into html.html.twig and
  // page.html.twig, the markup for the maintenance page is all in the single
  // maintenance-page.html.twig template. So, to have what’s done in
  // copycat_preprocess_html() and copycat_preprocess_page() also happen
  // on the maintenance page, it has to be called here.
  copycat_preprocess_html($vars, $hook);
  copycat_preprocess_page($vars, $hook);
}

/**
 * Implements hook_preprocess_page().
 */
function copycat_preprocess_page(&$vars, $hook) {
}
