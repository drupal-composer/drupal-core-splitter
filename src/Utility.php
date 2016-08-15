<?php

/**
 * @file
 * Contains \DrupalCoreSplit\Utility.
 */

namespace DrupalCoreSplit;

class Utility {

  public static function filterValidTags(array $tags, $branch) {
    $blacklist = array(
      '8.0.0-alpha14',
      '8.0.0-alpha15',
      '8.0.0-beta1',
      '8.0.0-beta2',
      '8.0.0-beta3',
      '8.0.0-beta4',
      '8.0.0-beta5',
    );

    list($major, $minor) = explode('.', $branch);
    $valid_tags = [];

    foreach ($tags as $tag) {
      preg_match('/refs\/tags\/(' . $major . '\.' . $minor . '\.[0-9]+[^\^\{}\n]*)/', $tag, $value);
      if (isset($value[1]) && !in_array($value[1], $blacklist)) {
        $valid_tags[] = $value[1];
      }
    }

    return array_unique($valid_tags);
  }

}
