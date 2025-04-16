<?php

namespace Drupal\purge_queuer_file_urls\Service;

/**
 * Defines an interface for services to filter files and URIs.
 */
interface StreamWrapperFilterServiceInterface {

  /**
   * Filter an array of files.
   *
   * @param \Drupal\File\FileInterface[] $files
   *   An array of files.
   *
   * @return \Drupal\File\FileInterface[]
   *   The filtered array of files.
   */
  public function filterFiles(array $files = []): array;

  /**
   * Filter an array of URIs.
   *
   * @param string[] $files
   *   An array of URIs.
   *
   * @return string[]
   *   The filtered array of URIs.
   */
  public function filterUris(array $uris = []): array;

}
