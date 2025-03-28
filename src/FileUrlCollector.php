<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\file\Plugin\Field\FieldType\FileItem;

/**
 * Helper class to collect file URLs from entities.
 */
class FileUrlCollector extends UrlCollectorBase {

  /**
   * {@inheritdoc}
   */
  public function collect(EntityInterface $entity) {
    if ($entity instanceof FieldableEntityInterface) {
      $field_definitions = $this->getFieldDefinitions($entity);
      $fields = $entity->getFields();
      /** @var \Drupal\Core\Field\FieldDefinitionInterface $field_definition */
      foreach (array_intersect_key($field_definitions, $fields) as $field_name => $field_definition) {
        // Checking if the field type class is a sublcass of FileItem will
        // ensure that all file-type fields are handled.
        if ($this->hasFieldTypeClass(FileItem::class, $field_definition)) {
          foreach ($entity->{$field_name} as $field_item) {
            yield from $this->urlExpressionFactory->generateFromFile($field_item->entity);
          }
        }
      }
    }
  }

}
