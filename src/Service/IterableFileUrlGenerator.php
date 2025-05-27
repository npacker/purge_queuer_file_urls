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
   * @param \Drupal\purge_queuer_file_urls\Servce\BaseUrlsProviderInterface $baseUrlsProvider
   *   The base URLs provider service.
   * @param bool $absolute
   *   (optional) Whether to return absolute or relative URLs.
   */
  public function __construct(
    protected readonly FileUrlGeneratorInterface $fileUrlGenerator,
    protected readonly BaseUrlsProviderInterface $baseUrlsProvider,
    protected ?bool $absolute = FALSE,
  ) {}

  /**
   * Creates a new IterableFileUrlGenerator instance.
   *
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator.
   * @param \Drupal\purge_queuer_file_urls\Service\BaseUrlsProviderInterface $base_urls_provider
   *   The base URLs provider.
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The configuration factory to retrieve absolute URL settings.
   *
   * @return \Drupal\purge_queuer_file_urls\Service\IterableFileUrlGenerator
   *   A new instance of the generator configured with the provided settings.
   */
  public static function create(FileUrlGeneratorInterface $file_url_generator, BaseUrlsProviderInterface $base_urls_provider, ConfigFactory $config_factory) {
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    $absolute_urls = $config->get('absolute_urls');
    return new static(
      $file_url_generator,
      $base_urls_provider,
      $absolute_urls,
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
    yield from $this->doGenerateString($uri, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function generateRelativeString(string $uri): \Generator {
    yield from $this->doGenerateString($uri);
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
    if ($absolute) {
      foreach ($this->baseUrlsProvider->iterateBaseUrls() as $base_url) {
        yield Url::fromUri($base_url . $this->fileUrlGenerator->generateString($uri), ['absolute' => $absolute]);
      }
    }
    else {
      yield $this->fileUrlGenerator->generate($uri)->setAbsolute($absolute);
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
      foreach ($this->baseUrlsProvider->iterateBaseUrls() as $base_url) {
        yield $base_url . $this->fileUrlGenerator->generateString($uri);
      }
    }
    else {
      yield $this->fileUrlGenerator->generateString($uri);
    }
  }

}
