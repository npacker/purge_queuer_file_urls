<?php

namespace Drupal\purge_queuer_file_urls\Service;

/**
 * Interface for invalidation type service classes.
 */
interface InvalidationTypeServiceInterface {

  /**
   * Get the invalidation type for file URLs.
   *
   * @return string
   *   The invalidation plugin ID.
   */
  public function getFileUrlInvalidationType();

  /**
   * Get the invalidation type for image style URLs.
   *
   * @return string
   *   The invalidation plugin ID.
   */
  public function getImageStyleUrlInvalidationType();

}
