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
    protected array $fileSchemes
  ) {}

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
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public static function create(
    FieldTypePluginManagerInterface $field_type_plugin_manager,
    EntityFieldManagerInterface $entity_field_manager,
    StreamWrapperManagerInterface $stream_wrapper_manager,
    UrlExpressionFactoryInterface $url_expression_factory,
    ConfigFactoryInterface $config_factory
  ) {
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    $file_schemes = $config->get('file_schemes');
    return new static(
      $field_type_plugin_manager,
      $entity_field_manager,
      $stream_wrapper_manager,
      $url_expression_factory,
      $file_schemes
    );
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
