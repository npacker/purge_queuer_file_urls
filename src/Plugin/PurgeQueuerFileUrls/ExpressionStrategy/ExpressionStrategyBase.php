<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ExpressionStrategyBase extends PluginBase implements ExpressionStrategyInterface, ContainerFactoryPluginInterface {

  /**
   * The file URL generator.
   *
   * @var \Drupal\Core\File\FileUrlGeneratorInterface
   */
  protected $fileUrlGenerator;

  /**
   * Whether to use absolute URLs.
   *
   * @var bool
   */
  protected $absoluteUrls;

  /**
   * Creates an ExpressionStrategy plugin instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file ULR generator.
   * @param bool $absolute_ruls
   *   Whether to use absolute URLs.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FileUrlGeneratorInterface $file_url_generator, bool $absolute_urls) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->fileUrlGenerator = $file_url_generator;
    $this->absoluteUrls = $absolute_urls;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $config_factory = $container->get('config.factory');
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('file_url_generator'),
      $config->get('absolute_urls')
    );
  }

}
