<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\image\ImageStyleInterface;

/**
 * Defines an interface for generating image style expressions.
 */
interface StyleExpressionStrategyInterface extends ExpressionStrategyInterface {

  /**
   * Generate an expression for the given image style.
   *
   * @param \Drupal\image\ImageStyleInterface $style
   *   An image style.
   *
   * @return Generator<\Drupal\purge_queuer_file_urls\UrlExpressionInterface>
   *   A generator that yields UrlExpressionInterface objects.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   */
  public function generateStyleExpression(ImageStyleInterface $style): \Generator;

}
