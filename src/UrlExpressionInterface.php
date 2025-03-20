<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;

interface UrlExpressionInterface {

  /**
   * Create the invalidation plugin correspending to this file URL expression.
   *
   * @param \string $invalidation_type
   *   The invalidation type plugin.
   * @param \Drupal\purge\Plugin\Purge\Invalidation\InvalidationServiceInterface $purge_invalidation_factory
   *   The purge invalidation factory.
   *
   * @return
   *   The invalidation plugin instance.
   */
  public function getInvalidation(string $invalidation_type, InvalidationsServiceInterface $purge_invalidation_factory);

}
