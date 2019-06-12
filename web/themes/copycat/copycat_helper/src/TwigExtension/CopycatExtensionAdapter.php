<?php

namespace Drupal\copycat_helper\TwigExtension;
/**
 * Load custom twig functions from Pattern Lab
 */
class CopycatExtensionAdapter extends \Twig_Extension {

  public function __construct() {
    CopycatExtensionLoader::init();
  }

  public function getFunctions() {
    return CopycatExtensionLoader::get();
  }

}
