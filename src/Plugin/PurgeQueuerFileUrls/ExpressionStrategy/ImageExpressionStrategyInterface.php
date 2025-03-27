<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\file\FileInterface;

/**
 * Defines an interface for generating expressions for all image style
 * derivatives for an individual image.
 */
interface ImageExpressionStrategyInterface extends PluginInspectionInterface {

  /**
   * Generate expressions for the given image based on all image styles.
   *
   * @param \Drupal\file\FileInterface $image
   *   An image.
   *
   * @return \Generator<UrlExpressionInterface>
   *   A generator yielding UrlExpressionInterface objects.
   */
  public function generateImageExpression(FileInterface $image): \Generator;

}
