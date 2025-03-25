<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

class ExpressionStrategyManager extends DefaultPluginManager {

  /**
   * Constructs a ExpressionStrategyManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/PurgeQueuerFileUrls/ExpressionStrategy',
      $namespaces,
      $module_handler,
      'Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\ExpressionStrategyInterface',
      'Drupal\purge_queuer_file_urls\Attribute\ExpressionStrategy'
    );
    $this->alterInfo('expression_strategy');
    $this->setCacheBackend($cache_backend, 'expression_strategy_plugins');
  }

}
