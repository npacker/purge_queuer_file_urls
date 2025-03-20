<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;

interface UrlExpressionInterface {

  /**
   * Create the invalidation plugin correspending to this file URL expression.
   *
   * @param \Drupal\purge\Plugin\Purge\Invalidation\InvalidationServiceInterface $purge_invalidation_factory
   *   The purge invalidation factory.
   *
   * @return \Drupal\purge\Plugin\Purge\Invalidation\InvalidationInterface
   *   The invalidation instance.
   */
  public function getInvalidation(InvalidationsServiceInterface $purge_invalidation_factory);

}
