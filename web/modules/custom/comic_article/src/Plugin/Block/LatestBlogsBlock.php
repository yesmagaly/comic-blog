<?php

namespace Drupal\comic_article\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'LatestBlogsBlock' block.
 *
 * @Block(
 *  id = "latest_blogs_block",
 *  admin_label = @Translation("Latest blogs block"),
 * )
 */
class LatestBlogsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['latest_blogs_block']['#markup'] = 'Implement LatestBlogsBlock.';

    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'article')
      ->condition('status', 1)
      ->sort('changed', 'DESC')
      ->pager(3)
      ->execute();

    $blogs = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->loadMultiple($nids);

    $output = \Drupal::entityTypeManager()
        ->getViewBuilder('node')
        ->viewMultiple($blogs, 'latest');
    return $output;
  }

}
