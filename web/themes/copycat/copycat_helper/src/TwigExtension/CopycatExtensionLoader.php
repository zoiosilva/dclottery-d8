<?php

namespace Drupal\copycat_helper\TwigExtension;

/**
 * Load custom twig functions from Pattern Lab.
 */
class CopycatExtensionLoader {

  /**
   * The array of twig functions.
   *
   * @var array
   */
  protected static $objects = [];

  /**
   * Initialize objects array if needed.
   */
  public static function init() {
    if (!self::$objects) {
      static::loadAll();
    }
  }

  /**
   * Getter for objects array.
   */
  public static function get() {
    return !empty(self::$objects) ? self::$objects : [];
  }

  /**
   * Populate the objects array.
   */
  protected static function loadAll() {
    $theme = \Drupal::config('system.theme')->get('default');
    $themeLocation = drupal_get_path('theme', $theme);
    $themePath = DRUPAL_ROOT . '/' . $themeLocation . '/';
    $fullPath = $themePath . 'source/_twig-components/functions/';
    static::load($fullPath . 'add_attributes.function.drupal.php');
  }

  /**
   * Load pattern lab file and add function to object list.
   */
  protected static function load($file) {
    include $file;
    self::$objects[] = $function;
  }

}
