<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Configurator for classes implementing the AbsoluteUrlsAwareInterface.
 *
 * Allows for injecting the absolute URLs settings.
 */
class AbsoluteUrlsAwareConfigurator {

  /**
   * Constructs a new AllowedUriSchemesAwareConfigurator object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(
    protected readonly ConfigFactoryInterface $configFactory,
  ) {}

  /**
   * Configure the given object implementing the AbsoluteUrlsAwareInterface.
   *
   * @param \Drupal\purge_queuer_file_urls\Service\AbsoluteUrlsAwareInterface $file_url_generator
   *   An absolute URLs aware object.
   */
  public function configure(AbsoluteUrlsAwareInterface $file_url_generator) {
    $config = $this->configFactory->get('purge_queuer_file_urls.settings');
    $file_url_generator->setAbsolute($config->get('absolute_urls'));
  }

}
