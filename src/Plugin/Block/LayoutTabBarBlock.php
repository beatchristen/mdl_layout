<?php

namespace Drupal\mdl_layout\Plugin\Block;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\ContentEntityTypeInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Tab bar' block.
 *
 * @Block(
 *   id = "mdl_layout_tab_bar_block",
 *   admin_label = @Translation("MDL Layout Tab Bar"),
 *   category = @Translation("Menus")
 * )
 */
class LayoutTabBarBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current route_match.
   *
   * @var RouteMatchInterface
   */
  protected $route_match;

  /**
   * @var ContentEntityInterface
   */
  protected $entity;

  /**
   * @var ContentEntityTypeInterface
   */
  protected $entity_type;

  /** @var string */
  protected $field_group_context;

  /**
   * Constructs a new SystemMenuBlock.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route_match.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->route_match = $route_match;

    $route_name = $this->route_match->getRouteName();

    $parts = explode('.', $route_name);
    $this->entity = NULL;
    if (count($parts) == 3 && $parts[0]=='entity' && in_array($parts[2], ['canonical', 'edit_form'])) {
      $this->entity = $this->route_match->getParameter($parts[1]);
      if (!is_null($this->entity)) {
        $this->field_group_context = $parts[2]=='canonical' ? 'view' : 'form';
        $this->entity_type = $this->entity->getEntityType();
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $route_name = $this->route_match->getRouteName();
    if (is_null($this->entity)) {
      return [];
    }

    $entity_type = $this->entity->getEntityTypeId();
    $bundle = $this->entity->bundle();
    $mode = 'default';

    $field_groups = field_group_info_groups($entity_type, $bundle, $this->field_group_context, $mode);

    // get the field_group for this group_type

    $build = [
      '#theme' => 'mdl_layout_tabbar',
      '#items' => [],
    ];

    foreach ($field_groups as $item) {
      if ($item->format_type == 'mdl_layout_tab_panel') {
        $href = '#' . $item->format_settings['id'];
        $label = $item->label;
        if (empty($label)) {
          $label = $item->format_settings['label'];
        }
        $build['#items'][ $href ] = $label;
      }
    }

    return $build;
  }

  public function getCacheMaxAge() {
    // FIXME we can do better than this...
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $cache_tags = parent::getCacheTags();
    if (!is_null($this->entity)) {
      $cache_tags[] = $this->entity->getCacheTags();
      $cache_tags[] = $this->entity_type->getListCacheTags();
      // TODO add dependency  on entity type config with the field_group third party settings
    }

    return $cache_tags;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $contexts = parent::getCacheContexts();
    if (!is_null($this->entity)) {
      $contexts = Cache::mergeContexts($contexts, $this->entity->getCacheContexts());
      $contexts = Cache::mergeContexts($contexts, $this->entity_type->getListCacheContexts());
      // TODO add dependency on entity type config with the field_group third party settings
    }

    return $contexts;
  }

}
