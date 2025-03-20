<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\Plugin\Field\FieldType\ImageItem;

/**
 * Helper class to collect image style URLs from entities.
 */
class ImageStyleUrlCollector extends UrlCollectorBase {

  /**
   * {@inheritdoc}
   */
  public function collect(EntityInterface $entity) {
    $urls = [];
    if (is_a($entity, FieldableEntityInterface::class)) {
      $entity_type_id = $entity->getEntityTypeId();
      $bundle = $entity->bundle();
      $entity_field_definitions = $this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle);
      $image_styles = ImageStyle::loadMultiple();
      /** @var \Drupal\Core\Field\FieldDefinitionInterface $field_definition */
      foreach ($entity_field_definitions as $entity_field_definition) {
        $field_type_id = $entity_field_definition->getType();
        $field_type_definition = $this->fieldTypePluginManager->getDefinition($field_type_id);
        $field_type_class = $field_type_definition['class'];
        if (is_a($field_type_class, ImageItem::class, TRUE)) {
          foreach ($entity->{$entity_field_definition->getName()} as $field_item) {
            $image_style_urls = [];
            /** @var \Drupal\file\FileInterface */
            $file_uri = $field_item->entity->getFileUri();
            /** @var \Drupal\image\Entity\ImageStyle $image_style */
            foreach ($image_styles as $image_style) {
              $image_style_uri = $image_style->buildUri($file_uri);
              $uri_regex = preg_replace('/(?<=\/)' . preg_quote($image_style->id(), '/') . '(?=\/)/', '.*', $image_style_uri);
              /** @var \Drupal\Core\Url */
              $image_style_urls[$uri_regex] = $this->fileUrlGenerator->generate($uri_regex);
            }
            $urls = array_merge($urls, array_values($image_style_urls));
          }
        }
      }
    }
    return $urls;
  }

}
