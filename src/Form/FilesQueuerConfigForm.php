<?php

namespace Drupal\purge_queuer_file_urls\Form;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Entity\EntityTypeBundleInfo;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Drupal\purge_ui\Form\QueuerConfigFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The configuration form for the file URLs queuer.
 */
class FilesQueuerConfigForm extends QueuerConfigFormBase {

  /**
   * Set the entity type manager
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Set the entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfo
   */
  protected $entityTypeBundleInfo;

  /**
   * The expression invalidation plugin manager.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $pluginManager;

  protected $streamWrapperManager;

  public static function create(ContainerInterface $container) {
    return parent::create($container)
      ->setEntityTypeManager($container->get('entity_type.manager'))
      ->setEntityTypeBundleInfo($container->get('entity_type.bundle.info'))
      ->setPluginManager($container->get('plugin.manager.expression_strategy'))
      ->setStreamWrapperManager($container->get('stream_wrapper_manager'));
  }

  /**
   * Set the entity type manager
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function setEntityTypeManager(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    return $this;
  }

  /**
   * Set the entity type bundle info.
   *
   * @param \Drupal\Core\Entity\EntityTypeBundleInfo $entity_type_bundle_info
   *   The entity type bundle info.
   */
  public function setEntityTypeBundleInfo(EntityTypeBundleInfo $entity_type_bundle_info) {
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
    return $this;
  }

  /**
   * Set the expression strategy plugin manager.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager
   *   The plugin manager.
   */
  public function setPluginManager(PluginManagerInterface $plugin_manager) {
    $this->pluginManager = $plugin_manager;
    return $this;
  }

  /**
   * Set the stream wrapper manager.
   *
   * @param \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface $stream_wrapper_manager
   */
  public function setStreamWrapperManager(StreamWrapperManagerInterface $stream_wrapper_manager) {
    $this->streamWrapperManager = $stream_wrapper_manager;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'purge_queuer_file_urls.configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['purge_queuer_file_urls.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('purge_queuer_file_urls.settings');
    $form['file_options'] = [
      '#type' => 'fieldset',
      '#title' => 'File Scheme Options',
      '#description' => $this->t('Include specific stream wrappers for invalidation.'),
    ];
    $scheme_options = $this->streamWrapperManager->getNames();
    $form['file_options']['file_schemes'] = [
      '#type' => 'checkboxes',
      '#multiple' => TRUE,
      '#options' => $scheme_options,
      '#default_value' => $config->get('file_schemes') ?? [],
    ];
    $form['url_options'] = [
      '#type' => 'fieldset',
      '#title' => 'Invalidation Options',
    ];
    $definitions = $this->pluginManager->getDefinitions();
    $file_expression_strategy_options = [];
    $image_expression_strategy_options = [];
    $derivative_expression_strategy_options = [];
    $style_expression_strategy_options = [];
    foreach ($definitions as $plugin_id => $definition) {
      if (in_array('file', $definition['supports'])) {
        $file_expression_strategy_options[$plugin_id] = $definition['label'];
      }
      if (in_array('image', $definition['supports'])) {
        $image_expression_strategy_options[$plugin_id] = $definition['label'];
      }
      if (in_array('derivative', $definition['supports'])) {
        $derivative_expression_strategy_options[$plugin_id] = $definition['label'];
      }
      if (in_array('style', $definition['supports'])) {
        $style_expression_strategy_options[$plugin_id] = $definition['label'];
      }
    }
    $form['url_options']['file_expression_strategy'] = [
      '#type' => 'select',
      '#title' => $this->t('Files'),
      '#description' => $this->t('Handles invalidation of all individual non-image files.'),
      '#options' => $file_expression_strategy_options,
      '#default_value' => $config->get('file_expression_strategy'),
    ];
    $form['url_options']['image_expression_strategy'] = [
      '#type' => 'select',
      '#title' => $this->t('Images'),
      '#description' => $this->t('Handles invalidation of individual image files as well as any image style derivatives for that image.'),
      '#options' => $image_expression_strategy_options,
      '#default_value' => $config->get('image_expression_strategy'),
    ];
    $form['url_options']['derivative_expression_strategy'] = [
      '#type' => 'select',
      '#title' => $this->t('Image derivatives'),
      '#description' => $this->t('Handles invalidation of individual image style derivatives for an image style.'),
      '#options' => $derivative_expression_strategy_options,
      '#default_value' => $config->get('derivative_expression_strategy'),
    ];
    $form['url_options']['style_expression_strategy'] = [
      '#type' => 'select',
      '#title' => $this->t('Image styles'),
      '#description' => $this->t('Handles invalidation of all derivaties for a given image style.'),
      '#options' => $style_expression_strategy_options,
      '#default_value' => $config->get('style_expression_strategy'),
    ];
    $form['url_options']['absolute_urls'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Absolute URLs'),
      '#description' => $this->t('The default form for URL expressions, unless otherwise specified by a plugin definition.'),
      '#default_value' => $config->get('absolute_urls'),
    ];
    $form['entity_types'] = [
      '#type' => 'container',
      '#markup' => $this->t('Configure entity type bundles to queue for file URL purging on entity update. If none are selected, all entity bundles will be eligible.'),
      '#tree' => TRUE,
    ];
    $entity_types = $config->get('entity_types') ?? [];
    $entity_type_definitions = $this->entityTypeManager->getDefinitions();
    foreach ($entity_type_definitions as $entity_type_definition) {
      if ($entity_type_definition instanceof ContentEntityType && is_a($entity_type_definition->getClass(), FieldableEntityInterface::class, TRUE)) {
        $entity_type_id = $entity_type_definition->id();
        $entity_type_label = $entity_type_definition->getLabel();
        $bundle_options = $this->getBundleOptions($entity_type_id);
        if (!empty($bundle_options)) {
          $form['entity_types'][$entity_type_id] = [
            '#type' => 'details',
            '#title' => $entity_type_label,
            '#open' => FALSE,
          ];
          $form['entity_types'][$entity_type_id]['bundles'] = [
            '#type' => 'checkboxes',
            '#multiple' => TRUE,
            '#options' => $bundle_options,
            '#default_value' => isset($entity_types[$entity_type_id]['bundles']) ? $entity_types[$entity_type_id]['bundles'] : [],
          ];
        }
      }
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitFormSuccess(array &$form, FormStateInterface $form_state) {
    $config = $this->config('purge_queuer_file_urls.settings');
    $config->set('file_expression_strategy', $form_state->getValue('file_expression_strategy'));
    $config->set('image_expression_strategy', $form_state->getValue('image_expression_strategy'));
    $config->set('derivative_expression_strategy', $form_state->getValue('derivative_expression_strategy'));
    $config->set('style_expression_strategy', $form_state->getValue('style_expression_strategy'));
    $config->set('absolute_urls', $form_state->getValue('absolute_urls'));
    $config->set('file_schemes', $form_state->getValue('file_schemes'));
    $config->set('entity_types', $form_state->getValue('entity_types'));
    $config->save();
  }

  /**
   * Get entity type bundle checkbox options.
   *
   * Populates an array of bundle labels for the given entity type id, keyed by
   * the machine name of the bundle.
   *
   * @param string $entity_type_id
   *   The entity type id.
   */
  protected function getBundleOptions($entity_type_id) {
    $bundles = $this->entityTypeBundleInfo->getBundleInfo($entity_type_id);
    $transformed_bundles = [];
    foreach ($bundles as $name => $bundle) {
      if (!empty($bundle['label'])) {
        $transformed_bundles[$name] = $bundle['label'];
      }
    }
    return $transformed_bundles;
  }

}
