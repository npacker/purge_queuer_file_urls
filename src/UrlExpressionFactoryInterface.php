<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\file\FileInterface;
use Drupal\image\ImageStyleInterface;

/**
 * Interface for factories that build URL expressions.
 */
interface UrlExpressionFactoryInterface {

  /**
   * Generate a file url expression for invalidation.
   *
   * @param \Drupal\file\FileInterface $file
   *   The file entity to generate an invalidation expression.
   *
   * @return \Generator<\Drupal\purge_queuer_file_urls\UrlExpressionInterface>
   *   A generator that yields UrlExpressionInterface objects.
   */
  public function generateFromFile(FileInterface $file): \Generator;

  /**
   * Generate an image style URL expression for invalidation.
   *
   * @param \Drupal\image\ImageStyleInterface $style
   *   The image style to generate an invalidation expression.
   * @param \string $path
   *   The path for the image style.
   *
   * @return \Generator<\Drupal\purge_queuer_file_urls\UrlExpressionInterface>
   *   A generator that yields UrlExpressionInterface objects.
   */
  public function generateFromStyle(ImageStyleInterface $style, string $path = ''): \Generator;

}
