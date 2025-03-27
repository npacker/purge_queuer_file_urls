<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Configurator class for URL collector objects.
 */
class UrlCollectorConfigurator {

  /**
   * Constructs a URL collector configurator.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(
    protected ConfigFactoryInterface $configFactory
  ) {}

  /**
   * Configure the given URL collector.
   *
   * @param \Drupal\purge_queuer_file_urls\UrlCollectorInterface $collector
   *   The URL collector to configure.
   */
  public function configure(UrlCollectorInterface $collector) {
    $config = $this->configFactory->get('purge_queuer_file_urls.settings');
    $collector->setFileSchemes($config->get('file_schemes'));
  }

}
