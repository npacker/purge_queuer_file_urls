<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Service helper for determining invalidation plugin type for a given
 * expression type.
 */
class InvalidationTypeService implements InvalidationTypeServiceInterface {

  /**
   * Whether URLs should be relative or absolute.
   *
   * @var bool
   */
  protected $absoluteUrls;

  /**
   * Constructs an InvalidationTypeService object.
   *
   * @param bool $absolute_urls
   *   Whether URLs should be relative or absolute.
   */
  public function __construct(bool $absolute_urls) {
    $this->absoluteUrls = $absolute_urls;
  }

  /**
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public static function create(ConfigFactoryInterface $config_factory) {
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    $absoulte_urls = $config->get('absolute_urls') ?? FALSE;
    return new static(
      $absolute_urls
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFileUrlInvalidationType() {
    return $this->absoluteUrls ? 'absoluteurl' : 'relativeurl';
  }

  /**
   * {@inheritdoc}
   */
  public function getImageStyleUrlInvalidationType() {
    return 'regex';
  }

}
