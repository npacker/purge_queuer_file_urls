<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\DerivativeExpressionStrategyInterface;
use Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\FileExpressionStrategyInterface;
use Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\ImageExpressionStrategyInterface;
use Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy\StyleExpressionStrategyInterface;

/**
 * Factory for building URL expresisons.
 */
class UrlExpressionFactory implements UrlExpressionFactoryInterface {

  /**
   * Create a new UrlExpressionFactory instance.
   *
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator.
   * @param bool $absolute_urls
   *   Whether to use absolute or relative URLs.
   */
  public function __construct(
    protected readonly FileUrlGeneratorInterface $fileUrlGenerator,
    protected readonly bool $absoluteUrls,
    protected readonly FileExpressionStrategyInterface $fileExpressionStrategy,
    protected readonly ImageExpressionStrategyInterface $imageExpressionStrategy,
    protected readonly DerivativeExpressionStrategyInterface $derivativeExpressionStrategy,
    protected readonly StyleExpressionStrategyInterface $styleExpressionStrategy
  ) {}

  /**
   * Factory method for UrlExpressionFactory instances.
   *
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   *   The expression strategy plugin manager.
   */
  public static function create(FileUrlGeneratorInterface $file_url_generator, ConfigFactoryInterface $config_factory, PluginManagerInterface $plugin_manager) {
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    $absolute_urls = $config->get('absolute_urls');
    $file_expression_strategy = $plugin_manager->createInstance($absolute_urls ? 'absoluteurl' : 'relativeurl');
    $image_expression_strategy = $plugin_manager->createInstance($absolute_urls ? 'absoluteurl' : 'relativeurl');
    $derivative_expression_strategy = $plugin_manager->createInstance($absolute_urls ? 'absoluteurl' : 'relativeurl');
    $style_expression_strategy = $plugin_manager->createInstance('regex');
    return new static(
      $file_url_generator,
      $absolute_urls,
      $file_expression_strategy,
      $image_expression_strategy,
      $derivative_expression_strategy,
      $style_expression_strategy
    );
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromFile(FileInterface $file) {
    return new FileUrlExpression($this->fileExpressionStrategy->getPluginId(), $this->fileExpressionStrategy->generateFileExpression($file));
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromImage(FileInterface $image) {
    foreach ($this->imageExpressionStrategy->generateImageExpression($image) as $expression) {
      yield new FileUrlExpression($this->imageExpressionStrategy->getPluginId(), $expression);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromStyle(ImageStyleInterface $style, string $path = '') {
    return empty($path) ?
      new ImageStyleUrlExpression($this->styleExpressionStrategy->getPluginId(), $this->styleExpressionStrategy->generateStyleExpression($style)) :
      new FileUrlExpression($this->derivativeExpressionStrategy->getPluginId(), $this->derivativeExpressionStrategy->generateDerivativeExpression($style, $path));
  }

}
