<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Url;

class ConfigurationAwareFileUrlGenerator implements AbsoluteUrlsAwareInterface, FileUrlGeneratorInterface {

  /**
   * Constructs a new ConfigurationAwareFileUrlGenerator object.
   *
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $inner
   *   The inner file URL generator.
   * @param bool $absolute
   *   (options) Whether to return absolute or relative URLs.
   */
  public function __construct(
    protected readonly FileUrlGeneratorInterface $inner,
    protected ?bool $absolute = FALSE,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function setAbsolute(bool $absolute): void {
    $this->absolute = $absolute;
  }

  /**
   * {@inheritdoc}
   */
  public function generateString(string $uri): string {
    return $this->inner->generateString($uri);
  }

  /**
   * {@inheritdoc}
   */
  public function generateAbsoluteString(string $uri): string {
    return $this->inner->generateAbsoluteString($uri);
  }

  /**
   * Create a web-accessible URL object, pre-configured based on the value of
   * the $absolute parameter.
   *
   * @return \Drupal\Core\Url
   *   A Url object with absolute URL. Use setAbsolute() on the Url object to
   *   change the final form of the URL before string conversion.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   *
   * @see \Drupal\purge_queuer_file_urls\Service\ConfigurationAwareFileUrlGenerator::$absolute
   */
  public function generate(string $uri): Url {
    return $this->inner->generate($uri)->setAbsolute($this->absolute);
  }

  /**
   * {@inheritdoc}
   */
  public function transformRelative(string $file_url, bool $root_relative = TRUE): string {
    return $this->inner->transformRelative($file_url, $root_relative);
  }

}
