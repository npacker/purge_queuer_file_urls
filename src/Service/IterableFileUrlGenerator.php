<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Url;

/**
 * File URL generator class that provides generator methods for creating file
 * URLs and modifies the generation of URLs based on configuration.
 */
class IterableFileUrlGenerator implements IterableFileUrlGeneratorInterface {

  /**
   * Constructs a new IterableFileUrlGenerator object.
   *
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $fileUrlGenerator
   *   The inner file URL generator.
   * @param bool $absolute
   *   (optional) Whether to return absolute or relative URLs.
   * @param array $baseUrs
   *   (optional) Additional base URLs for generation.
   */
  public function __construct(
    protected readonly FileUrlGeneratorInterface $fileUrlGenerator,
    protected ?bool $absolute = FALSE,
    protected ?array $baseUrls = [],
  ) {}

  public static function create(FileUrlGeneratorInterface $file_url_generator, ConfigFactory $config_factory) {
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    $absolute_urls = $config->get('absolute_urls');
    $base_urls = $config->get('base_urls');
    return new static(
      $file_url_generator,
      $absolute_urls,
      $base_urls,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function generate(string $uri): \Generator {
    yield from $this->doGenerate($uri, $this->absolute);
  }

  /**
   * {@inheritdoc}
   */
  public function generateAbsolute(string $uri): \Generator {
    yield from $this->doGenerate($uri, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function generateRelative(string $uri): \Generator {
    yield from $this->doGenerate($uri);
  }

  /**
   * {@inheritdoc}
   */
  public function generateString(string $uri): \Generator {
    yield from $this->doGenerateString($uri, $this->absolute);
  }

  /**
   * {@inheritdoc}
   */
  public function generateAbsoluteString(string $uri): \Generator {
    yield from $this->doGenerateAbsoluteString($uri, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function generateRelativeString(string $uri): \Generator {
    yield from $this->doGenerateRelativeString($uri);
  }

  /**
   * Generates web-accessible URL objects.
   *
   * @param string $uri
   *   The URI to a file for which we need an external URL, or the path to a
   *   shipped file.
   * @param bool $absolute
   *   Whether to generate absolute or relative URL objects.
   *
   * @return \Drupal\Core\Url
   *   A Url object with absolute URL. Use setAbsolute() on the Url object to
   *   change the final form of the URL before string conversion.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   */
  protected function doGenerate(string $uri, bool $absolute = FALSE): \Generator {
    yield $this->fileUrlGenerator->generate($uri)->setAbsolute($absolute);
    if ($absolute) {
      foreach ($this->baseUrls as $base_url) {
        yield Url::fromUri($base_url . $this->fileUrlGenerator->generateString($uri), ['absolute' => $absolute]);
      }
    }
  }

  /**
   * Generates web-accessible URL strings.
   *
   * @param string $uri
   *   The URI to a file for which we need an external URL, or the path to a
   *   shipped file.
   * @param bool $absolute
   *   Whether to generate absolute or relative URL strings.
   *
   * @return string
   *   For a local URL (matching domain), a string containing the URL that may
   *   be used to access the file.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   */
  protected function doGenerateString(string $uri, bool $absolute = FALSE): \Generator {
    if ($absolute) {
      $this->fileUrlGenerator->generateAbsoluteString($uri);
      foreach ($this->baseUrls as $base_url) {
        yield $base_url . $this->fileUrlGenerator->generateString($uri);
      }
    }
    else {
      yield $this->fileUrlGenerator->generateString($uri);
    }
  }

}
