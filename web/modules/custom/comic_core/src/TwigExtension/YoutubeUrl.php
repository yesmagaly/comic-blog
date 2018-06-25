<?php

namespace Drupal\comic_core\TwigExtension;

class YoutubeUrl extends \Twig_Extension {

  /**
   * Generates a list of all Twig filters that this extension defines.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('youtube_url', array($this, 'urlNormalizer')),
    ];
  }

  /**
   * Gets a unique identifier for this Twig extension.
   */
  public function getName() {
    return 'comic_core.twig_extension';
  }

  /**
   * Normalize the default youtube url to be embed.
   */
  public static function urlNormalizer($url) {
    return str_replace('watch?v=', 'embed/', $url);
  }

}
