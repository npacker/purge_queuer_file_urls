<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\image\ImageStyleInterface;

/**
 * Defines an interface for generating individual image style derivatie
 * expressions.
 */
interface DerivativeExpressionStrategyInterface extends ExpressionStrategyInterface {

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
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   */
  public function generateDerivativeExpression(ImageStyleInterface $style, string $path): \Generator;

}
