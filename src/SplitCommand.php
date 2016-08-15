<?php

/**
 * @file
 * Contains \DrupalCoreSplit\SplitCommand.
 */

namespace DrupalCoreSplit;

use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

class SplitCommand {

  protected $upstream = 'https://git.drupal.org/project/drupal.git';

  protected $downstream = 'https://github.com/drupal-composer/drupal-core.git';

  public function handle(Args $args, IO $io) {
    $directory = 'upstream-current';

    // Update local repository.
    $this->updateRepository($directory);

    // Update branch
    $this->splitBranch($directory, $args->getArgument('branch'));

    // Update tags
    exec('git ls-remote --tags ' . $this->upstream, $upstream_tags);
    exec('git ls-remote --tags ' . $this->downstream, $downstream_tags);

    $upstream_tags = Utility::filterValidTags($upstream_tags, $args->getArgument('branch'));
    $downstream_tags = Utility::filterValidTags($downstream_tags, $args->getArgument('branch'));

    // Tags which are not in the downstream repo.
    $tags = array_diff($upstream_tags, $downstream_tags);
    array_walk($tags, function ($tag) use ($directory) {
      $this->splitTag($directory, $tag);
    });
  }

  protected function updateRepository($directory) {
    if (!file_exists($directory)) {
      passthru("git clone {$this->upstream} {$directory}");
    }
    passthru("cd {$directory} && git remote set-url origin {$this->upstream} && git fetch origin && git fetch -t origin ");
  }

  protected function splitBranch($directory, $ref) {
    passthru("cd {$directory} && git checkout --force {$ref} && git reset --hard origin/{$ref}");
    passthru("./splitsh-lite --progress --prefix=core/ --origin=origin/{$ref} --path={$directory} --target=HEAD");
    passthru("cd {$directory} && git push {$this->downstream} HEAD:{$ref}");
  }

  protected function splitTag($directory, $ref) {
    passthru("./splitsh-lite --progress --prefix=core/ --origin=tags/{$ref} --path={$directory} --target=HEAD");
    passthru("cd {$directory} && git tag -f {$ref} HEAD && git push {$this->downstream} {$ref}");
  }

}
