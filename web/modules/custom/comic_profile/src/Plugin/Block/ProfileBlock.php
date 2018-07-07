<?php

namespace Drupal\comic_profile\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ProfileBlock' block.
 *
 * @Block(
 *  id = "profile_block",
 *  admin_label = @Translation("Profile block"),
 * )
 */
class ProfileBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['profile_block']['#markup'] = 'Implement ProfileBlock.';

    $profile = \Drupal::entityTypeManager()
        ->getStorage('profile')
        ->load(1);

    $output = \Drupal::entityTypeManager()
        ->getViewBuilder('profile')
        ->view($profile, 'banner');

    return $output;
  }

}
