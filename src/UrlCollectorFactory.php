<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;

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
    $styles = $this->getStyles();
    return new ImageStyleUrlCollector(
      $this->fieldTypePluginManager,
      $this->entityFieldManager,
      $this->urlExpressionFactory,
      $styles,
    );
  }

  protected function getStyles() {
    $storage = $this->entityTypeManager->getStorage('image_style');
    return $storage->loadMultiple();
  }

}
