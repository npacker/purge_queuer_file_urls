<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Config\ConfigFactoryInterface;
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
class EntityUpdateService {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

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
   * The file URLs queuer.
   *
   * @var \Drupal\purge_queuer_file_urls\FileUrlsQueuerInterface
   */
  protected $fileUrlsQueuer;

  /**
   * Configuration for the file URLs queuer.
   *
   * @var array|null
   */
  protected $config;

  /**
   * Construct a new EntityUpdateService object.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_plugin_manager
   *   The field type plugin manager.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\purge_queuer_file_urls\FileUrlsQueuerInterface $file_urls_queuer
   *   The file URLs queuer.
   * @param array|null $config
   *   Configuration for the file URLs queuer.
   */
  public function __construct(FieldTypePluginManagerInterface $field_type_plugin_manager, EntityFieldManagerInterface $entity_field_manager, FileUrlGeneratorInterface $file_url_generator, FileUrlsQueuerInterface $file_urls_queuer, $config) {
    $this->fieldTypePluginManager = $field_type_plugin_manager;
    $this->entityFieldManager = $entity_field_manager;
    $this->fileUrlGenerator = $file_url_generator;
    $this->fileUrlsQueuer = $file_urls_queuer;
    $this->config = $config;
  }

  /**
   * Factory method for the EntityUpdateService class.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_plugin_manager
   *   The field type plugin manager.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\purge_queuer_file_urls\FileUrlsQueuerInterface $file_urls_queuer
   *   The file URLs queuer.
   * @param \Druapal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public static function create(FieldTypePluginManagerInterface $field_type_plugin_manager, EntityFieldManagerInterface $entity_field_manager, FileUrlGeneratorInterface $file_url_generator, FileUrlsQueuerInterface $file_urls_queuer, ConfigFactoryInterface $config_factory) {
    return new static(
      $field_type_plugin_manager,
      $entity_field_manager,
      $file_url_generator,
      $file_urls_queuer,
      $config_factory->get('purge_queuer_file_urls.settings'),
    );
  }

  /**
   * Handle an entity update.
   *
   * Invalidates the URLs of any files referenced through fields on the entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The updated entity.
   */
  public function handleEntityUpdate(EntityInterface $entity, EntityInterface $original) {
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
      $field_name = $entity_field_definition->getName();
      foreach ($entity->{$field_name} as $delta => $field_item) {
        /** @var \Drupal\file\FileInterface $original_file */
        $original_file = $original->{$field_name}->get($delta)->entity;
        $original_file_uri = $original_file->getFileUri();
        /** @var \Drupal\file\FileInterface $file */
        $file = $field_item->entity;
        $file_uri = $file->getFileUri();
        if ($original_file->getSize() == $file->getSize() && \sha1_file($original_file_uri) == \sha1_file($file_uri)) {
          continue;
        }
        $urls[] = $this->fileUrlGenerator->generate($file_uri);
        // If this is an image field, we need to account for image styles.
        if (!is_a($field_type_class, ImageItem::class, TRUE)) {
          continue;
        }
        $image_styles = ImageStyle::loadMultiple();
        /** @var \Drupal\image\Entity\ImageStyle $image_style */
        foreach ($image_styles as $image_style) {
          $image_style_uri = $image_style->buildUri($file_uri);
          $urls[] = $this->fileUrlGenerator->generate($image_style_uri);
        }
      }
    }
    $this->fileUrlsQueuer->invalidateUrls($urls, $this->config->get('absolute_urls'));
  }

}
