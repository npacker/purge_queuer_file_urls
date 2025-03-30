<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\UrlExpressionInterface;

/**
 * Defines an interface for generating individual image style derivatie
 * expressions.
 */
interface DerivativeExpressionStrategyInterface extends PluginInspectionInterface {

  /**
   * Generate an expression for the given image style derivative.
   *
   * @param \Drupal\image\ImageStyleInterface $style
   *   An image style.
   * @param string $path
   *   The path to an individual style derivative.
   *
   * @return Generator<\Drupal\purge_queuer_file_urls\UrlExpressionInterface>
   *   A generator that yields UrlExpressionInterface objects.
   */
  public function generateDerivativeExpression(ImageStyleInterface $style, string $path): \Generator;

}
