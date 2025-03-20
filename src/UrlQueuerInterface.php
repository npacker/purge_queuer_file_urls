<?php

namespace Drupal\purge_queuer_file_urls;

interface UrlQueuerInterface {

  /**
   * Invalidate the given array of file URLs.
   *
   * URLs should be absolute URLs as would be registered in the caching layer
   * and presented to the client.
   *
   * @param iterable $urls
   *   A generator that yields URL expressions.
   */
  public function invalidateUrls(iterable $urls);

}
