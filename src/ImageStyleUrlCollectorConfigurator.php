<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Configurator class for image style URL collector objects.
 */
class ImageStyleUrlCollectorConfigurator {

  /**
   * Constructs a image style URL collector configurator.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manger.
   */
  public function __construct(
    private UrlCollectorConfigurator $inner,
    protected EntityTypeManagerInterface $entityTypeManager
  ) {}

  /**
   * Configure the given URL collector.
   *
   * @param \Drupal\purge_queuer_file_urls\UrlCollectorInterface $collector
   *   The URL collector to configure.
   */
  public function configure(ImageStyleUrlCollector $collector) {
    $this->inner->configure($collector);
    $storage = $this->entityTypeManager->getStorage('image_style');
    $collector->setStyles($storage->loadMultiple());
  }

}
