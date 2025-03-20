<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldTypePluginManagerInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;

/**
 * Base class for URLs collector classes.
 */
abstract class UrlCollectorBase implements UrlCollectorInterface {

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
   * The URL expression factory.
   *
   * @var \Drupal\purge_queuer_file_urls\FileUrlExpressionFactoryInterface $urlExpressionFactory
   */
  protected $urlExpressionFactory;

  /**
   * Construct a new EntityUpdateService object.
   *
   * @param \Drupal\Core\Field\FieldTypePluginManagerInterface $field_type_plugin_manager
   *   The field type plugin manager.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\purge_queuer_file_urls\UrlExpressionFactoryInterface $url_expression_factory
   *   The URL expression factory.
   */
  public function __construct(
    FieldTypePluginManagerInterface $field_type_plugin_manager,
    EntityFieldManagerInterface $entity_field_manager,
    UrlExpressionFactoryInterface $url_expression_factory
  ) {
    $this->fieldTypePluginManager = $field_type_plugin_manager;
    $this->entityFieldManager = $entity_field_manager;
    $this->urlExpressionFactory = $url_expression_factory;
  }

  /**
   * {@inheritdoc}
   */
  abstract public function collect(EntityInterface $entity);

}
