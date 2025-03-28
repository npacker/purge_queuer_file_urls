<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Drupal\file\FileInterface;

/**
 * Provides a service for filtering files based on their stream wrapper.
 */
class StreamWrapperFilterService implements StreamWrapperFilterServiceInterface, AllowedUriSchemesAwareInterface {

  /**
   * The allowd stream wrappers.
   *
   * @var string[]
   */
  protected $allowedUriSchemes = [];

  /**
   * Constructs a new StreamWrapperFilterService object.
   *
   * @param \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface $streamWrapperManager
   *   The stream wrapper manager.
   */
  public function __construct(
    protected readonly StreamWrapperManagerInterface $streamWrapperManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function setAllowedUriSchemes(array $allowed_uri_schemes): void {
    $this->allowedUriSchemes = $allowed_uri_schemes;
  }

  /**
   * {@inheritdoc}
   */
  public function filterFiles(array $files = []): array {
    return array_filter($files, function (FileInterface $file) {
      $uri_scheme = $this->streamWrapperManager->getScheme($file->getFileUri());
      return $this->isAllowedUriScheme($uri_scheme);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function filterUris(array $uris = []): array {
    return array_filter($uris, function (string $uri) {
      $uri_scheme = $this->streamWrapperManager->getScheme($uri);
      return $this->isAllowedUriScheme($uri_scheme);
    });
  }

  /**
   * Determine if the given URI scheme is allowed.
   *
   * @param string $uri_scheme
   *   A URI scheme string.
   *
   * @return bool
   *   Whether the given URI scheme is allowed.
   */
  protected function isAllowedUriScheme(string $uri_scheme): bool {
    return $this->allowedUriSchemes && in_array($uri_scheme, $this->allowedUriSchemes);
  }

}
