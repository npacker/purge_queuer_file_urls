<?php

namespace Drupal\purge_queuer_file_urls\Service;

/**
 * Interface for a service that yields configured base URLs.
 */
interface BaseUrlsProviderInterface {

  /**
   * Returns the configured base URLs.
   *
   * @return Generator<string> A generator that yields base URL strings.
   */
  public function iterateBaseUrls(): \Generator;

}