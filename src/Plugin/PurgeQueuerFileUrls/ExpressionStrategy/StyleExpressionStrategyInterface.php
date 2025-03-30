<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\UrlExpressionInterface;

/**
 * Defines an interface for generating image style expressions.
 */
interface StyleExpressionStrategyInterface extends PluginInspectionInterface {

  /**
   * Generate an expression for the given image style.
   *
   * @param \Drupal\image\ImageStyleInterface $style
   *   An image style.
   *
   * @return Generator<\Drupal\purge_queuer_file_urls\UrlExpressionInterface>
   *   A generator that yields UrlExpressionInterface objects.
   */
  public function generateStyleExpression(ImageStyleInterface $style): \Generator;

}
