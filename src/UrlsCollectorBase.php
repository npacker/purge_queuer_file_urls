<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;

/**
 * Base class for URLs collector classes.
 */
abstract class UrlsCollectorBase implements UrlsCollectorInterface {

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
   * {@inheritdoc}
   */
  abstract public function collect(EntityInterface $entity);

}
