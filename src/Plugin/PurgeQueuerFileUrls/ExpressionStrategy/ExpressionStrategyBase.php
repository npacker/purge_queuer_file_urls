<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\purge_queuer_file_urls\Service\IterableFileUrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for expression strategy plugins.
 *
 * Plugins that do not extend this class heirarchy will need to impelement the
 * ExpressionStrategyInterface explicitely.
 */
abstract class ExpressionStrategyBase extends PluginBase implements ExpressionStrategyInterface, ContainerFactoryPluginInterface {

  /**
   * Creates an ExpressionStrategy plugin instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\purge_queuer_file_urls\Service\IterableFileUrlGeneratorInterface $fileUrlGenerator
   *   The file ULR generator.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected readonly IterableFileUrlGeneratorInterface $fileUrlGenerator,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('purge_queuer_file_urls.file_url_generator'),
    );
  }

}
