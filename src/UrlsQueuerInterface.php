<?php

namespace Drupal\purge_queuer_file_urls;

interface UrlsQueuerInterface {

  /**
   * Invalidate the given array of file URLs.
   *
   * URLs should be absolute URLs as would be registered in the caching layer
   * and presented to the client.
   *
   * @param array $urls
   *   The array of file urls.
   */
  public function invalidateUrls(array $urls);

}
