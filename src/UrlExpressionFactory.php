<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
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
   * @var bool
   */
  protected $absoluteUrls;

  /**
   * Create a new UrlExpressionFactory instance.
   *
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator.
   * @param bool $absolute_urls
   *   Whether to use absolute or relative URLs.
   */
  public function __construct(FileUrlGeneratorInterface $file_url_generator, bool $absolute_urls) {
    $this->fileUrlGenerator = $file_url_generator;
    $this->absoluteUrls = $absolute_urls;
  }

  /**
   * Factory method for UrlExpressionFactory instances.
   *
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public static function create(FileUrlGeneratorInterface $file_url_generator, ConfigFactoryInterface $config_factory) {
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    $absolute_urls = $config->get('absolute_urls');
    return new static(
      $file_url_generator,
      $absolute_urls
    );
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromFile(FileInterface $file) {
    $file_uri = $file->getFileUri();
    /** @var \Drupal\Core\Url $url */
    $file_url = $this->fileUrlGenerator->generate($file_uri);
    return new FileUrlExpression($this->absoluteUrls ? 'absoluteurl' : 'relativeurl', $file_url);
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromStyle(ImageStyleInterface $style, string $path = '') {
    $style_uri = $style->buildUri($path);
    /** @var \Drupal\Core\Url $url */
    $file_url = $this->fileUrlGenerator->generate($style_uri)->setAbsolute($this->absoluteUrls)->toString();
    if (empty($path)) {
      return new ImageStyleUrlExpression('regex', '^' . $file_url . '/.*$');
    }
    else {
      return new FileUrlExpression($this->absoluteUrls ? 'absoluteurl' : 'relativeurl', $file_url);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromImage(FileInterface $image) {
    $image_uri = $image->getFileUri();
    $image_styles = ImageStyle::loadMultiple();
    foreach ($image_styles as $image_style) {
      $image_style_uri = $image_style->buildUri($image_uri);
      $image_style_url = $this->fileUrlGenerator->generate($image_style_uri);
      yield new FileUrlExpression($this->absoluteUrls ? 'absoluteurl' : 'relativeurl', $image_style_url);
      // $image_style_url = $this->fileUrlGenerator->generate($image_style_uri)->setAbsolute($this->absoluteUrls)->toString();
      // $regex = preg_replace('/(?<=\/)' . preg_quote($image_style->id(), '/') . '(?=\/)/', '.*', $image_style_url);
      // yield new ImageStyleUrlExpression('regex', $regex);
    }
  }

}
