<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Defines a configurator for classes implementing the ImageStyleAwareInterface.
 */
class ImageStyleAwareConfigurator {

  /**
   * Constructs a new ImageStyleAwareConfigurator.
   *
   * @parma \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(
    protected EntityTypeManagerInterface $entityTypeManager,
  ) {}

  /**
   * Configure the given class implementing the ImageStyleAwareInterface.
   *
   * @param \Drupal\purge_queuer_file_urls\ImageStyleAwareInterface $image_style_url_collector
   *   An image style URL collector.
   */
  public function configure(ImageStyleAwareInterface $image_style_url_collector) {
    $storage = $this->entityTypeManager->getStorage('image_style');
    $image_style_url_collector->setImageStyles($storage->loadMultiple());
  }

}
