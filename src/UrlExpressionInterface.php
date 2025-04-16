<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationInterface;

/**
 * Defines an interface for URL expressions used in invalidation.
 *
 * This interface is used to create invalidation plugins corresponding to file
 * URL expressions. It provides a method to generate an invalidation instance
 * based on the URL expression.
 */
interface UrlExpressionInterface {

  /**
   * Create the invalidation plugin corresponding to this file URL expression.
   *
   * @param \Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface $purge_invalidation_factory
   *   The purge invalidation factory.
   *
   * @return \Drupal\purge\Plugin\Purge\Invalidation\InvalidationInterface
   *   The invalidation instance.
   */
  public function getInvalidation(InvalidationsServiceInterface $purge_invalidation_factory): InvalidationInterface;

}
