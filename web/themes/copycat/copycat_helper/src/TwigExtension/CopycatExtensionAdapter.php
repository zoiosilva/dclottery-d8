<?php

namespace Drupal\copycat_helper\TwigExtension;

/**
 * Load custom twig functions from Pattern Lab.
 */
class CopycatExtensionAdapter extends \Twig_Extension {

  /**
   * Initializes CopycatExtensionLoader.
   */
  public function __construct() {
    CopycatExtensionLoader::init();
  }

  /**
   * Gets CopycatExtensionLoader functions.
   */
  public function getFunctions() {
    return CopycatExtensionLoader::get();
  }

}
