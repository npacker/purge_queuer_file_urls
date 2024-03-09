<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityFieldManager;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldTypePluginManager;
use Drupal\file\Plugin\Field\FieldType\FileItem;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\Plugin\Field\FieldType\ImageItem;

/**
 * Helper class to handle entity updates.
 */
class EntityUpdateService {

  /**
   * The field type plugin manager.
   *
   * @var \Drupal\Core\Field\FieldTypePluginManager
   */
  protected $fieldTypePluginManager;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entityFieldManager;

  /**
   * The file URLs queuer.
   *
   * @var \Drupal\purge_queuer_file_urls\FileUrlsQueuerInterface
   */
  protected $fileUrlsQueuer;

  /**
   * Construct a new EntityUpdateService object.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManager $field_type_plugin_manager
   *   The field type plugin manager.
   * @param \Drupal\Core\Entity\EntityFieldManager $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\purge_queuer_file_urls\FileUrlsQueuerInterface $file_urls_queuer
   *   The file URLs queuer.
   */
  public function __construct(FieldTypePluginManager $field_type_plugin_manager, EntityFieldManager $entity_field_manager, FileUrlsQueuerInterface $file_urls_queuer) {
    $this->fieldTypePluginManager = $field_type_plugin_manager;
    $this->entityFieldManager = $entity_field_manager;
    $this->fileUrlsQueuer = $file_urls_queuer;
  }

  /**
   * Handle an entity update.
   *
   * Invalidates the URLs of any files referenced through fields on the entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The updated entity.
   */
  public function handleEntityUpdate(EntityInterface $entity) {
    // Skip non-fieldable entities.
    if (!($entity instanceof FieldableEntityInterface)) {
      return;
    }

    $urls = [];
    $entity_type_id = $entity->getEntityTypeId();
    $bundle = $entity->bundle();
    $entity_field_definitions = $this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle);

    /** @var \Drupal\Core\Field\FieldDefinitionInterface $field_definition */
    foreach ($entity_field_definitions as $entity_field_definition) {
      $field_type_id = $entity_field_definition->getType();
      $field_type_definition = $this->fieldTypePluginManager->getDefinition($field_type_id);
      $field_type_class = $field_type_definition['class'];

      // We are only concerned with file fields.
      if (!is_a($field_type_class, FileItem::class, TRUE)) {
        continue;
      }

      /** @var \Drupal\file\FileInterface $file */
      $file = $entity->{$entity_field_definition->getName()}->entity;
      $file_url = $file->createFileUrl(FALSE);
      $urls[] = $file_url;

      // If this is an image field, we need to account for image styles.
      if (is_a($field_type_class, ImageItem::class, TRUE)) {
        $file_uri = $file->getFileUri();
        $image_styles = ImageStyle::loadMultiple();

        /** @var \Drupal\image\Entity\ImageStyle $image_style */
        foreach ($image_styles as $image_style) {
          $image_style_url = $image_style->buildUrl($file_uri);
          $urls[] = $image_style_url;
        }
      }
    }

    $this->fileUrlsQueuer->invalidateUrls($urls);
  }

}
