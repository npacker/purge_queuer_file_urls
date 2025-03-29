<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\image\Plugin\Field\FieldType\ImageItem;

/**
 * Helper class to collect image style URLs from entities.
 */
class ImageStyleUrlCollector extends UrlCollectorBase implements ImageStyleAwareInterface {

  /**
   * The array of image styles.
   *
   * @var string[]
   */
  protected $styles = [];

  /**
   * {@inheritdoc}
   */
  public function setImageStyles(array $image_styles): void {
    $this->styles = $image_styles;
  }

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
        if ($this->hasFieldTypeClass(ImageItem::class, $field_definition)) {
          foreach ($this->styles as $style) {
            foreach ($entity->{$field_name} as $field_item) {
              yield from $this->urlExpressionFactory->generateFromStyle($style, $field_item->entity->getFileUri());
            }
          }
        }
      }
    }
  }

}
