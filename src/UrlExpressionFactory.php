<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\file\FileInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\DerivativeExpressionStrategyInterface;
use Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\FileExpressionStrategyInterface;
use Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\StyleExpressionStrategyInterface;

/**
 * Factory for building URL expressions.
 */
class UrlExpressionFactory implements UrlExpressionFactoryInterface {

  /**
   * Create a new UrlExpressionFactory instance.
   *
   * @param Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\DerivativeExpressionStrategyInterface $fileExpressionStrategy
   *   The expression generation strategy to use for files.
   * @param Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\ImageExpressionStrategyInterface $derivativeExpressionStrategy
   *   The expression generation strategy to use for image derivatives.
   * @param Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\StyleExpressionStrategyInterface $styleExpressionStrategy
   *   The expression generation strategy to use for image styles.
   */
  public function __construct(
    protected readonly FileExpressionStrategyInterface $fileExpressionStrategy,
    protected readonly DerivativeExpressionStrategyInterface $derivativeExpressionStrategy,
    protected readonly StyleExpressionStrategyInterface $styleExpressionStrategy,
  ) {}

  /**
   * Factory method for UrlExpressionFactory instances.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   *   The expression strategy plugin manager.
   */
  public static function create(ConfigFactoryInterface $config_factory, PluginManagerInterface $plugin_manager) {
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    return new static(
      $plugin_manager->createInstance($config->get('file_expression_strategy')),
      $plugin_manager->createInstance($config->get('derivative_expression_strategy')),
      $plugin_manager->createInstance($config->get('style_expression_strategy'))
    );
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromFile(FileInterface $file): \Generator {
    yield from $this->fileExpressionStrategy->generateFileExpression($file);
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromStyle(ImageStyleInterface $style, string $path = ''): \Generator {
    yield from empty($path) ?
      $this->styleExpressionStrategy->generateStyleExpression($style) :
      $this->derivativeExpressionStrategy->generateDerivativeExpression($style, $path);
  }

}
