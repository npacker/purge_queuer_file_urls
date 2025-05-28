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
  public function filterFiles(iterable $files): \Generator {
    foreach ($files as $file) {
      $uri_scheme = $this->streamWrapperManager->getScheme($file->getFileUri());
      if ($this->isAllowedUriScheme($uri_scheme)) {
        yield $file;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function filterUris(iterable $uris): \Generator {
    foreach ($uris as $uri) {
      $uri_scheme = $this->streamWrapperManager->getScheme($uri);
      if ($this->isAllowedUriScheme($uri_scheme)) {
        yield $uri;
      }
    }
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
