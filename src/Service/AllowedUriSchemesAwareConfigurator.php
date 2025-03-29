<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Configurator for classes implementing the AllowedUriSchemesAwareInterface.
 *
 * Allows for injecting the allowed file schemes setting.
 */
class AllowedUriSchemesAwareConfigurator {

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
   * Configure the given object impelmenting the
   * AllowedUriSchemesAwareInterface.
   *
   * @param \Drupal\purge_queuer_file_urls\Service\AllowedUriSchemesAwareInterface $stream_wrapper_filter
   *   An allowed URI schemes aware object.
   */
  public function configure(AllowedUriSchemesAwareInterface $stream_wrapper_filter) {
    $config = $this->configFactory->get('purge_queuer_file_urls.settings');
    $stream_wrapper_filter->setAllowedUriSchemes($config->get('file_schemes'));
  }

}
