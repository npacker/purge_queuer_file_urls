<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\image\Plugin\Field\FieldType\ImageItem;

/**
 * Helper class to collect image style URLs from entities.
 */
class ImageStyleUrlCollector extends UrlCollectorBase {

  /**
   * {@inheritdoc}
   */
  public function collect(EntityInterface $entity) {
    if ($entity instanceof FieldableEntityInterface) {
      $field_definitions = $this->getFieldDefinitions($entity);
      $fields = $entity->getFields();
      /** @var \Drupal\Core\Field\FieldDefinitionInterface $field_definition */
      foreach (array_intersect_key($field_definitions, $fields) as $field_name => $field_definition) {
        $field_type_class = $this->getFieldTypeClass($field_definition);
        // Checking if the field type class is a sublcass of ImageItem will
        // ensure that all image-type fields are handled.
        if (is_a($field_type_class, ImageItem::class, TRUE)) {
          foreach ($entity->{$field_name} as $field_item) {
            yield from $this->urlExpressionFactory->generateFromImage($field_item->entity);
          }
        }
      }
    }
  }

}
