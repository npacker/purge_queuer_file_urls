<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * Base class for field caching services.
 */
abstract class FieldCacheBase {

  /**
   * The fully-qualified field type class to cache.
   *
   * @var string
   */
  protected $fieldTypeClass;

  /**
   * Field definitions.
   *
   * @var \Drupal\Core\Field\FieldDefinitionInterface[]
   */
  protected $fieldDefinitions = [];

  /**
   * Constructs a FileFieldCache object.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache backend.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   *   The entity field manager.
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $fieldTypePluginManager
   *   The field type plugin manager.
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   */
  public function __construct(
    protected readonly CacheBackendInterface $cache,
    protected readonly EntityFieldManagerInterface $entityFieldManager,
    protected readonly FieldTypePluginManagerInterface $fieldTypePluginManager,
    protected readonly LanguageManagerInterface $languageManager,
  ) {}

  /**
   * Retrieves cached field definitions for the given entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity for which to retrieve cached field definitions.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   *   The array of field definitions for the bundle, keyed by field name.
   */
  public function getFieldDefinitions(EntityInterface $entity) {
    if ($entity instanceof FieldableEntityInterface) {
      $entity_type_id = $entity->getEntityTypeId();
      $bundle = $entity->bundle();
      $langcode = $this->languageManager->getCurrentLanguage()->getId();
      if (!isset($this->fieldDefinitions[$entity_type_id][$bundle][$langcode])) {
        $cid = $this->getCacheId($entity_type_id, $bundle, $this->fieldTypeClass, $langcode);
        $data = [];
        if ($cache = $this->cache->get($cid)) {
          $data = $cache->data;
        }
        else {
          $data = $this->rebuildFieldDefinitions($entity_type_id, $bundle);
        }
        $this->fieldDefinitions[$entity_type_id][$bundle][$langcode] = $data;
      }
    }
    return $this->fieldDefinitions[$entity_type_id][$bundle][$langcode] ?? [];
  }

  /**
   * Rebuild the field definitions cache for the given entity type and bundle.
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
  protected function rebuildFieldDefinitions(string $entity_type_id, string $bundle) {
    $data = [];
    /** @var \Drupal\Core\Field\FieldDefinitionInterface[] $field_definitions */
    $field_definitions = $this->entityFieldManager->getFieldDefinitions($entity_type_id, $bundle);
    foreach ($field_definitions as $field_name => $field_definition) {
      $field_type_id = $field_definition->getType();
      $field_type_definition = $this->fieldTypePluginManager->getDefinition($field_type_id);
      $field_type_class = $field_type_definition['class'];
      if (is_a($field_type_class, $this->fieldTypeClass, TRUE)) {
        $data[$field_name] = $field_definition;
      }
    }
    $langcode = $this->languageManager->getCurrentLanguage()->getId();
    $cid = $this->getCacheId($entity_type_id, $bundle, $this->fieldTypeClass, $langcode);
    $this->cache->set($cid, $data, CacheBackendInterface::CACHE_PERMANENT, [
      'entity_types',
      'entity_field_info',
    ]);
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
   * @param string $field_type_class
   *   The field type.
   * @param string $langcode
   *   The current language code.
   *
   * @return string
   *   The generated cache ID.
   */
  protected function getCacheId($entity_type_id, $bundle, $field_type_class, $langcode) {
    return "purge_queuer_file_urls:{$entity_type_id}:{$bundle}:{$field_type_class}:{$langcode}";
  }

}
