<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\file\FileInterface;
use Drupal\purge_queuer_file_urls\UrlExpressionInterface;

/**
 * Defines an interface for generating individual file expressions.
 */
interface FileExpressionStrategyInterface extends ExpressionStrategyInterface {

  /**
   * Generate an invalidation expression for the given file.
   *
   * @param \Drupal\file\FileInterface $file
   *   A file.
   *
   * @return Generator<\Drupal\purge_queuer_file_urls\UrlExpressionInterface>
   *   A generator that yields UrlExpressionInterface objects.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   */
  public function generateFileExpression(FileInterface $file): \Generator;

}
