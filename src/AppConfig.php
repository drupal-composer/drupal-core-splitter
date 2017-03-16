<?php

/**
 * @file
 * Contains \DrupalCoreSplit\AppConfig.
 */

namespace DrupalCoreSplit;

use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Config\DefaultApplicationConfig;

class AppConfig extends DefaultApplicationConfig {

  protected function configure() {
    parent::configure();

    $this->beginCommand('split')
      ->addArgument('branch', Argument::REQUIRED)
      ->setHandler(new SplitCommand());
  }

}
