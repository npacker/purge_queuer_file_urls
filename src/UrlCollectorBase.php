<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;

/**
 * Base class for URLs collector classes.
 */
abstract class UrlCollectorBase implements UrlCollectorInterface {

  /**
   * Construct a new EntityUpdateService object.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $fieldTypePluginManager
   *   The field type plugin manager.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   *   The entity field manager.
   * @param \Drupal\purge_queuer_file_urls\UrlExpressionFactoryInterface $urlExpressionFactory
   *   The URL expression factory.
   */
  public function __construct(
    protected FieldTypePluginManagerInterface $fieldTypePluginManager,
    protected EntityFieldManagerInterface $entityFieldManager,
    protected UrlExpressionFactoryInterface $urlExpressionFactory,
  ) {}

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
   * @param string $class_name
   *   The field type class name.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The field definition.
   *
   * @return bool
   *   Whether the field definition has the given class.
   */
  protected function hasFieldTypeClass(string $class_name, FieldDefinitionInterface $field_definition) {
    $field_type_id = $field_definition->getType();
    $field_type_definition = $this->fieldTypePluginManager->getDefinition($field_type_id);
    $field_type_class = $field_type_definition['class'] ?? NULL;
    return $field_type_class && is_a($field_type_class, $class_name, TRUE);
  }

}
