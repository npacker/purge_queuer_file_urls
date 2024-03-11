<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\file\Plugin\Field\FieldType\FileItem;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\Plugin\Field\FieldType\ImageItem;

/**
 * Helper class to handle entity updates.
 */
class FileUrlsCollector {

  /**
   * The field type plugin manager.
   *
   * @var \Drupal\Core\Field\FieldTypePluginManagerInterface
   */
  protected $fieldTypePluginManager;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * The file URL generator.
   *
   * @var \Drupal\Core\File\FileUrlGeneratorInterface
   */
  protected $fileUrlGenerator;

  /**
   * Construct a new EntityUpdateService object.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_plugin_manager
   *   The field type plugin manager.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\Core\File\FileUrlGeneratorInterface $file_url_generator
   *   The file URL generator.
   */
  public function __construct(FieldTypePluginManagerInterface $field_type_plugin_manager, EntityFieldManagerInterface $entity_field_manager, FileUrlGeneratorInterface $file_url_generator) {
    $this->fieldTypePluginManager = $field_type_plugin_manager;
    $this->entityFieldManager = $entity_field_manager;
    $this->fileUrlGenerator = $file_url_generator;
  }

  /**
   * Collect file URLs from an entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The updated entity.
   * @return \Drupal\Core\Url[]
   *   The array of URL objects.
   */
  public function collect(EntityInterface $entity) {
    if (!is_a($entity, FieldableEntityInterface::class)) {
      return;
    }
    $urls = [];
    $entity_type_id = $entity->getEntityTypeId();
    $bundle = $entity->bundle();
    $entity_field_definitions = $this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle);
    $image_styles = ImageStyle::loadMultiple();
    /** @var \Drupal\Core\Field\FieldDefinitionInterface $field_definition */
    foreach ($entity_field_definitions as $entity_field_definition) {
      $field_type_id = $entity_field_definition->getType();
      $field_type_definition = $this->fieldTypePluginManager->getDefinition($field_type_id);
      $field_type_class = $field_type_definition['class'];
      if (!is_a($field_type_class, FileItem::class, TRUE)) {
        continue;
      }
      $field_name = $entity_field_definition->getName();
      foreach ($entity->{$field_name} as $delta => $field_item) {
        /** @var \Drupal\file\FileInterface $file */
        $file = $field_item->entity;
        $file_uri = $file->getFileUri();
        $urls[] = $this->fileUrlGenerator->generate($file_uri);
        if (!is_a($field_type_class, ImageItem::class, TRUE)) {
          continue;
        }
        /** @var \Drupal\image\Entity\ImageStyle $image_style */
        foreach ($image_styles as $image_style) {
          $image_style_uri = $image_style->buildUri($file_uri);
          $urls[] = $this->fileUrlGenerator->generate($image_style_uri);
        }
      }
    }
    return $urls;
  }

}
