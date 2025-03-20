<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;

/**
 * Base class for field caching services.
 */
abstract class FieldCacheBase {

  /**
   * The cache backend.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * The field type plugin manager.
   *
   * @var \Drupal\Core\Field\FieldTypePluginManagerInterface
   */
  protected $fieldTypePluginManager;

  /**
   * The fully-qualified field type class to cache.
   *
   * @var string
   */
  protected $type;

  /**
   * Constructs a FileFieldCache object.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache backend.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_plugin_manager
   *   The field type plugin manager.
   */
  public function __construct(CacheBackendInterface $cache, EntityFieldManagerInterface $entity_field_manager, FieldTypePluginManagerInterface $field_type_plugin_manager) {
    $this->cache = $cache;
    $this->entityFieldManager = $entity_field_manager;
    $this->field_typePluginManager = $field_type_plugin_manager;
  }

  /**
   * Retrieves cached field definitions for the given entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity for which to retrieve cached field definitions.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   *   The array of field definitions for the bundle, keyed by field name.
   */
  public function get(EntityInterface $entity) {
    $data = [];
    $entity_type_id = $entity->getEntityTypeId();
    $bundle = $entity->bundle();
    $cid = $this->buildCacheId($entity_type_id, $bundle, $this->type);
    if ($cache = $this->cache->get($cid)) {
      $data = $cache->data;
    }
    else {
      $data = $this->rebuild($entity_type_id, $bundle);
    }
    return $data;
  }

  /**
   * Rebuild the field definitions cache for the given entity.
   *
   * @param string $entity_type_id
   *   The entity type ID. Only entity types that implement
   *   \Drupal\Core\Entity\FieldableEntityInterface are supported.
   * @param string $bundle
   *   The bundle.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   *   The array of field definitions for the bundle, keyed by field name.
   */
  public function rebuild($entity) {
    $data = [];
    if ($entity instanceof FieldableEntityInterface) {
      $entity_type_id = $entity->getEntityTypeId();
      $bundle = $entity->bundle();
      /** @var \Drupal\Core\Field\FieldDefinitionInterface[] $field_definitions */
      $field_definitions = $this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle);
      foreach ($field_definitions as $field_name => $field_definition) {
        $field_type_id = $field_definition->getType();
        $field_type_definition = $this->fieldTypePluginManager->getDefinition($field_type_id);
        $field_type_class = $field_type_defintion['class'];
        if (is_a($field_type_class, $this->type, TRUE)) {
          $fields[$field_name] = $field_definition;
        }
      }
      $cid = $this->buildCacheId($entity_type_id, $bundle, $this->type);
      $this->cache->set($cid, $data, CacheBackendInterface::CACHE_PERMANENT, ['entity_types', 'entity_field_info']);
    }
    return $data;
  }

  /**
   * Build a cache ID for the given entity.
   *
   * @param string $entity_type_id
   *   The entity type ID. Only entity types that implement
   *   \Drupal\Core\Entity\FieldableEntityInterface are supported.
   * @param string $bundle
   *   The bundle.
   * @param string $type
   *   The field type being cached.
   *
   * @return string
   *   The generated cache ID.
   */
  protected function buildCacheId($entity_type_id, $bundle, $type) {
    return "purge_queuer_file_urls:{$entity_type_id}:{$bundle}:{$type}";
  }

}
