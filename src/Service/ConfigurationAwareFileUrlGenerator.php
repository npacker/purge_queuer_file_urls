<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\File\FileUrlGeneratorInterface;

/**
 * File URL generator class that provides generator methods for creating file
 * URLs and modifies the generation of URLs based on configuration.
 */
class ConfigurationAwareFileUrlGenerator implements IterableFileUrlGeneratorInterface {

  /**
   * Constructs a new ConfigurationAwareFileUrlGenerator object.
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
    yield $this->fileUrlGenerator->generate($uri)->setAbsolute($this->absolute);
  }

  /**
   * {@inheritdoc}
   */
  public function generateString(string $uri): \Generator {
    yield $this->absolute ?
      $this->fileUrlGenerator->generateAbsoluteString($uri) :
      $this->fileUrlGenerator->generateString($uri);
  }

}
