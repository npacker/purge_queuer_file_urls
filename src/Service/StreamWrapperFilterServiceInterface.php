<?php

namespace Drupal\purge_queuer_file_urls\Service;

/**
 * Defines an interface for services to filter files and URIs.
 */
interface StreamWrapperFilterServiceInterface {

  /**
   * Filter an iterable of files.
   *
   * @param iterable $files
   *   An iterable of files.
   *
   * @return \Generator
   *   A generator of filtered files.
   */
  public function filterFiles(iterable $files): \Generator;

  /**
   * Filter an iterable of URIs.
   *
   * @param iterable $uris
   *   An iterable of URIs.
   *
   * @return \Generator
   *   A generator of filtered URIs.
   */
  public function filterUris(iterable $uris): \Generator;

}
