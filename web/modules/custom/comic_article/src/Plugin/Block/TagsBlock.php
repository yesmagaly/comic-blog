<?php

namespace Drupal\comic_article\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Cache\Cache;

/**
 * Provides a 'TagsBlock' block.
 *
 * @Block(
 *  id = "tags_block",
 *  admin_label = @Translation("Tags block"),
 * )
 */
class TagsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\Core\Entity\EntityManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;
  /**
   * Constructs a new TagsBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityManagerInterface $entity_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entity_manager;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'tags__block';

    $path = \Drupal::service('path.current')->getPath();
    $params = \Drupal\Core\Url::fromUserInput($path)->getRouteParameters();

    if (isset($params['node'])) {
      $node = \Drupal\node\Entity\Node::load($params['node']);
      $tags = $node->get('field_tags');

      # More info: https://www.flocondetoile.fr/blog/render-programmatically-unique-field-node-or-entity-drupal-8
      $renderFieldTags = \Drupal::entityTypeManager()->getViewBuilder('node')
        ->viewField($tags, 'full');

      $build['#content'] = $renderFieldTags;

      # Get caches.
      $cacheTags = Cache::mergeTags(['comic:tags:block'], $node->getCacheTags());

      # More info about cache context : https://www.drupal.org/docs/8/api/cache-api/cache-contexts
      $cacheContexts = Cache::mergeTags(['url'], $node->getCacheContexts());

      # Configure block caches.
      # more info: https://weknowinc.com/blog/drupal-8-add-cache-metadata-render-arrays
      $build['#cache'] = [
        'tags' => $cacheTags,
        'contexts' => $cacheContexts
      ];
    }

    return $build;
  }

}
