<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Config\ConfigFactoryInterface;

class AllowedUriSchemesAwareConfigurator {

  public function __construct(
    protected readonly ConfigFactoryInterface $configFactory,
  ) {}

  public function configure(AllowedUriSchemesAwareInterface $stream_wrapper_filter) {
    $config = $this->configFactory->get('purge_queuer_file_urls.settings');
    $stream_wrapper_filter->setAllowedUriSchemes($config->get('file_schemes'));
  }

}
