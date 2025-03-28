<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;

/**
 * Factory for constructing UrlCollector objects.
 */
class UrlCollectorFactory {

  public function __construct(
    protected FieldTypePluginManagerInterface $fieldTypePluginManager,
    protected EntityFieldManagerInterface $entityFieldManager,
    protected EntityTypeManagerInterface $entityTypeManager,
    protected UrlExpressionFactoryInterface $urlExpressionFactory,
  ) {}

  public function createFileUrlCollector() {
    return new FileUrlCollector(
      $this->fieldTypePluginManager,
      $this->entityFieldManager,
      $this->urlExpressionFactory,
    );
  }

  public function createImageStyleUrlCollector() {
    $collector = new ImageStyleUrlCollector(
      $this->fieldTypePluginManager,
      $this->entityFieldManager,
      $this->urlExpressionFactory,
    );
    $collector->setImageStyles($this->getStyles());
    return $collector;
  }

  protected function getStyles() {
    $storage = $this->entityTypeManager->getStorage('image_style');
    return $storage->loadMultiple();
  }

}
