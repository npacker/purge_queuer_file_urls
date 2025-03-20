<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\file\FileInterface;
use Drupal\image\ImageStyleInterface;

/**
 * Factory for building URL expresisons.
 */
class UrlExpressionFactory implements UrlExpressionFactoryInterface {

  /**
   * The file URL generator.
   *
   * @var \Drupal\Core\File\FileUrlGeneratorInterface
   */
  protected $fileUrlGenerator;

  /**
   * Whether to use absolute or relative URLs.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(FileUrlGeneratorInterface $file_url_generator, ConfigFactoryInterface $config_factory) {
    $this->fileUrlGenerator = $file_url_generator;
    $this->config = $config_factory->get('purge_queuer_file_urls.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromFile(FileInterface $file) {
    $absolute_urls = $this->config->get('absolute_urls');
    $file_uri = $file->getFileUri();
    /** @var \Drupal\Core\Url $url */
    $file_url = $this->fileUrlGenerator->generate($file_uri);
    return new FileUrlExpression($absolute_urls ? 'absoluteurl' : 'relativeurl', $file_url);
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromStyle(ImageStyleInterface $style, string $path = '') {
    $absolute_urls = $this->config->get('absolute_urls');
    $style_uri = $style->buildUri($path);
    /** @var \Drupal\Core\Url $url */
    $file_url = $this->fileUrlGenerator->generate($style_uri);
    if (empty($path)) {
      return new ImageStyleUrlExpression('regex', '^' . $file_url->setAbsolute($absolute_urls)->toString() . '/.*$');
    }
    else {
      return new FileUrlExpression($absolute_urls ? 'absoluteurl' : 'relativeurl', $file_url);
    }
  }

}
