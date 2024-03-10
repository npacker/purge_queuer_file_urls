<?php

namespace Drupal\purge_queuer_file_urls\Form;

use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Entity\EntityTypeBundleInfo;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\purge_ui\Form\QueuerConfigFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The configuration form for the file URLs queuer.
 */
class FileUrlsQueuerConfigForm extends QueuerConfigFormBase {

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

  public static function create(ContainerInterface $container) {
    return parent::create($container)
      ->setEntityTypeManager($container->get('entity_type.manager'))
      ->setEntityTypeBundleInfo($container->get('entity_type.bundle.info'));
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
    $form['url_options'] = [
      '#type' => 'fieldset',
      '#title' => 'Url Invalidation Options',
    ];
    $form['url_options']['absolute_urls'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Absolute Urls'),
      '#description' => $this->t('Urls can be purged as <em>absolute</em> ("http://www.site.com/path/file.ext") or <em>base-relative</em> ("/path/file.ext"). <strong>Ensure that a purger with the corresponding type is configured under the "Cache Invalidation" options.</strong>'),
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
        $options = $this->getBundleOptions($entity_type_id);
        if (!empty($options)) {
          $form['entity_types'][$entity_type_id] = [
            '#type' => 'details',
            '#title' => $entity_type_label,
            '#open' => FALSE,
          ];
          $form['entity_types'][$entity_type_id]['bundles'] = [
            '#type' => 'checkboxes',
            '#multiple' => TRUE,
            '#options' => $options,
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
    $config->set('absolute_urls', $form_state->getValue('absolute_urls'));
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
    array_walk($bundles, function (&$bundle, $name) {
      $bundle = $bundle['label'];
    });
    return $bundles;
  }

}
