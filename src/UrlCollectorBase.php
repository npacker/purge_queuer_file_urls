<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;

/**
 * Base class for URLs collector classes.
 */
abstract class UrlCollectorBase implements UrlCollectorInterface {

  /**
   * The file schemes to include for collection.
   *
   * @var string[]
   */
  protected $fileSchemes;

  /**
   * Construct a new EntityUpdateService object.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $fieldTypePluginManager
   *   The field type plugin manager.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   *   The entity field manager.
   * @param \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface $streamWrapperManager
   *   The stream wrapper manager.
   * @param \Drupal\purge_queuer_file_urls\UrlExpressionFactoryInterface $urlExpressionFactory
   *   The URL expression factory.
   * @param string[] $fileSchemes
   *   The file schemes to include for collection.
   */
  public function __construct(
    protected FieldTypePluginManagerInterface $fieldTypePluginManager,
    protected EntityFieldManagerInterface $entityFieldManager,
    protected StreamWrapperManagerInterface $streamWrapperManager,
    protected UrlExpressionFactoryInterface $urlExpressionFactory,
  ) {}

  /**
   * Set the file schemes to include for collection.
   *
   * @param string[] $file_schemes
   *   An array of file schemes.
   */
  public function setFileSchemes(array $file_schemes) {
    $this->fileSchemes = $file_schemes;
  }

  /**
   * {@inheritdoc}
   */
  abstract public function collect(EntityInterface $entity);

  /**
   * Get the field definitions for the given entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The updated entity.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   *   The array of field definitions.
   */
  protected function getFieldDefinitions(EntityInterface $entity) {
    $entity_type_id = $entity->getEntityTypeId();
    $bundle = $entity->bundle();
    return $this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle);
  }

  /**
   * Get the field type class for the given field definition.
   *
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The field definition.
   *
   * @return string
   *   The class name.
   */
  protected function getFieldTypeClass(FieldDefinitionInterface $field_definition) {
    $field_type_id = $field_definition->getType();
    $field_type_definition = $this->fieldTypePluginManager->getDefinition($field_type_id);
    return $field_type_definition['class'];
  }

}
