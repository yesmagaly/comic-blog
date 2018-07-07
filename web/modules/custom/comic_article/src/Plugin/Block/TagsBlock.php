<?php

namespace Drupal\comic_article\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'TagsBlock' block.
 *
 * @Block(
 *  id = "tags_block",
 *  admin_label = @Translation("Tags block"),
 * )
 */
class TagsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['tags_block']['#markup'] = 'Implement TagsBlock.';

    $tids = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'tags')
      ->sort('changed', 'DESC')
      ->execute();

    $output = array_map(function ($tid) {
      $amout = $this->getCountNodesByTag($tid);
      $data = $this->getTagsMetadata($tid);
      $data['amout'] = $amout;

      return $data;
    }, $tids);
    
    return [
      '#theme' => 'tags__block',
      '#tags' => $output,
      '#cache' => [
        'max-age' => 60,
      ],
    ];
  }

  public function getCountNodesByTag ($tid) {
    return \Drupal::entityQuery('node')
      ->condition('field_tags', [$tid], 'IN')
      ->condition('type', 'article')
      ->count()
      ->execute();
  }

  public function getTagsMetadata ($tid) {
    $tag = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->load($tid);

    $url = \Drupal::service('pathauto.generator')
      ->createEntityAlias($tag, 'return');

    return [
      'name' => $tag->get('name')->value,
      'url' => $url
    ];
  }


}
