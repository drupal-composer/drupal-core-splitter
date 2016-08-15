<?php

/**
 * @file
 * Contains \DrupalCoreSplit\LegacyPushTagsCommand.
 */

namespace DrupalCoreSplit;

use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class LegacyPushTagsCommand {

  public function handle(Args $args, IO $io) {
    define('UPSTREAM_REPOSITORY', 'https://git.drupal.org/project/drupal.git');
    define('UPSTREAM_DIRECTORY', 'upstream');
    define('DOWNSTREAM_REPOSITORY', exec('git config --get remote.origin.url'));

    exec('git ls-remote --tags ' . UPSTREAM_REPOSITORY, $upstream_tags);
    exec('git ls-remote --tags ' . DOWNSTREAM_REPOSITORY, $downstream_tags);

    $upstream_tags = Utility::filterValidTags($upstream_tags, $args->getArgument('branch'));
    $downstream_tags = Utility::filterValidTags($downstream_tags, $args->getArgument('branch'));

    // Tags which are not in the downstream repo.
    $tags = array_diff($upstream_tags, $downstream_tags);

    passthru('./subtree-split fetch');
    foreach ($tags as $tag) {
      passthru('./subtree-split push tag ' . escapeshellarg($tag));
    }
  }

}
