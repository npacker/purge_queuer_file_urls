<?php

namespace Drupal\purge_queuer_file_urls\Form;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Config\Config;
use Drupal\Core\Entity\EntityTypeBundleInfo;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Drupal\purge_ui\Form\QueuerConfigFormBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * The configuration form for the file URLs queuer.
 */
class FilesQueuerConfigForm extends QueuerConfigFormBase {

  /**
   * Set the entity type manager.
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

  /**
   * The stream wrapper manager.
   *
   * @var \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface
   */
  protected $streamWrapperManager;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return parent::create($container)
      ->setEntityTypeManager($container->get('entity_type.manager'))
      ->setEntityTypeBundleInfo($container->get('entity_type.bundle.info'))
      ->setPluginManager($container->get('plugin.manager.expression_strategy'))
      ->setStreamWrapperManager($container->get('stream_wrapper_manager'))
      ->setRequestStack($container->get('request_stack'));
  }

  /**
   * Set the entity type manager.
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
   *   The stream wrapper manager.
   */
  public function setStreamWrapperManager(StreamWrapperManagerInterface $stream_wrapper_manager) {
    $this->streamWrapperManager = $stream_wrapper_manager;
    return $this;
  }

  /**
   * Set the request stack.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function setRequestStack(RequestStack $request_stack) {
    $this->requestStack = $request_stack;
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
    $form['url_options'] = $this->buildUrlOptionsForm($form_state, $config);
    $form['file_options'] = $this->buildFileOptionsForm($form_state, $config);
    $form['base_urls'] = $this->buildBaseUrlsOptionsForm($form_state, $config);
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValue('base_urls') as $delta => $base_url) {
      if (!empty($base_url) && !UrlHelper::isValid($base_url, TRUE)) {
        $form_state->setErrorByName("base_urls][$delta", 'Enter URLs in a valid format.');
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitFormSuccess(array &$form, FormStateInterface $form_state) {
    $config = $this->config('purge_queuer_file_urls.settings');
    $config->set('file_expression_strategy', $form_state->getValue('file_expression_strategy'));
    $config->set('derivative_expression_strategy', $form_state->getValue('derivative_expression_strategy'));
    $config->set('style_expression_strategy', $form_state->getValue('style_expression_strategy'));
    $config->set('absolute_urls', $form_state->getValue('absolute_urls'));
    $config->set('file_schemes', $form_state->getValue('file_schemes') ?? []);
    $config->set('base_urls', $this->processBaseUrls($form_state->getValue('base_urls') ?? []));
    $config->save();
  }

  /**
   * Build the file options form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   * @param \Drupal\Core\Config\Config $config
   *   The configuration object.
   *
   * @return array
   *   The file options form array.
   */
  protected function buildFileOptionsForm(FormStateInterface $form_state, Config $config) {
    $scheme_options = $this->streamWrapperManager->getNames();
    return [
      '#type' => 'fieldset',
      '#title' => $this->t('File Scheme Options'),
      '#description' => $this->t('Include specific stream wrappers for invalidation.'),
      'file_schemes' => [
        '#type' => 'checkboxes',
        '#multiple' => TRUE,
        '#options' => $scheme_options,
        '#default_value' => $config->get('file_schemes') ?? [],
      ],
    ];
  }

  /**
   * Build the URL options form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   * @param \Drupal\Core\Config\Config $config
   *   The configuration object.
   *
   * @return array
   *   The URL options form array.
   */
  protected function buildUrlOptionsForm(FormStateInterface $form_state, Config $config) {
    $definitions = $this->pluginManager->getDefinitions();
    $file_expression_strategy_options = $this->getExpressionStrategyOptions($definitions, 'file');
    $derivative_expression_strategy_options = $this->getExpressionStrategyOptions($definitions, 'derivative');
    $style_expression_strategy_options = $this->getExpressionStrategyOptions($definitions,'style');
    return [
      '#type' => 'fieldset',
      '#title' => $this->t('Invalidation Options'),
      '#description' => $this->t('<strong>Ensure that a compatible purger is configured for each expression type selected.</strong>'),
      'file_expression_strategy' => [
        '#type' => 'select',
        '#title' => $this->t('Files'),
        '#description' => $this->t('Handles expression generation for individual files.'),
        '#options' => $file_expression_strategy_options,
        '#default_value' => $config->get('file_expression_strategy'),
      ],
      'derivative_expression_strategy' => [
        '#type' => 'select',
        '#title' => $this->t('Image derivatives'),
        '#description' => $this->t('Handles expression generation for individual image style derivatives.'),
        '#options' => $derivative_expression_strategy_options,
        '#default_value' => $config->get('derivative_expression_strategy'),
      ],
      'style_expression_strategy' => [
        '#type' => 'select',
        '#title' => $this->t('Image styles'),
        '#description' => $this->t('Handles expression generation for all derivatives for a given image style.'),
        '#options' => $style_expression_strategy_options,
        '#default_value' => $config->get('style_expression_strategy'),
      ],
      'absolute_urls' => [
        '#type' => 'checkbox',
        '#title' => $this->t('Absolute URLs'),
        '#description' => $this->t('The default form for URL expressions, unless otherwise specified by a plugin definition.'),
        '#default_value' => $config->get('absolute_urls'),
      ],
    ];
  }

  /**
   * Build the base URLs options form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   * @param \Drupal\Core\Config\Config $config
   *   The configuration object.
   *
   * @return array
   *   The base URLs form array.
   */
  protected function buildBaseUrlsOptionsForm(FormStateInterface $form_state, Config $config) {
    $form = [
      '#type' => 'fieldset',
      '#title' => $this->t('Base URLs'),
      '#description' => $this->t('Configure additional base URLs to invalidate. <strong>Only applies if at least one invalidation type is configured to output absolute URLs.</strong>'),
      'base_urls' => [
        '#type' => 'container',
        '#prefix' => '<div id="base-urls-wrapper">',
        '#suffix' => '</div>',
        '#tree' => TRUE,
      ],
      'actions' => [
        'add_base_url' => [
          '#type' => 'submit',
          '#name' => 'add_base_url',
          '#value' => $this->t('Add base URL'),
          '#submit' => [
            [$this, 'addBaseUrlSubmit'],
          ],
          '#ajax' => [
            'callback' => [$this, 'addBaseUrlCallback'],
            'wrapper' => 'base-urls-wrapper',
            'effect' => 'fade',
          ],
        ],
      ],
    ];
    $current_base_url = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
    $base_urls = $form_state->getValue('base_urls') ?? $config->get('base_urls');
    foreach ($base_urls as $delta => $base_url) {
      $form['base_urls'][$delta] = [
        '#type' => 'textfield',
        '#default_value' => $base_url,
        '#placeholder' => $current_base_url,
      ];
    }
    $form['base_urls'][] = [
      '#type' => 'textfield',
      '#placeholder' => $current_base_url,
    ];
    return $form;
  }

  /**
   * Submit handler for add_base_url button.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function addBaseUrlSubmit(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild();
  }

  /**
   * AJAX callback for add_base_url button.
   *
   * @param array $form
   *   The form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   */
  public function addBaseUrlCallback(array &$form, FormStateInterface $form_state) {
    return $form['base_urls']['base_urls'];
  }

  /**
   * Get expression strategy options.
   *
   * @param array $definitions
   *   The plugin definitions.
   * @param string $type
   *   The type of expression strategy.
   *
   * @return array
   *   The expression strategy options.
   */
  protected function getExpressionStrategyOptions(array $definitions, string $type) {
    $options = [];
    foreach ($definitions as $plugin_id => $definition) {
      if (in_array($type, $definition['supports'])) {
        $options[$plugin_id] = $definition['label'];
      }
    }
    return $options;
  }

  /**
   * Helper function to process base URLs by trimming trailing slashes and filtering out empty strings.
   *
   * @param array $base_urls
   *   The array of base URLs to process.
   *
   * @return array
   *   The processed array of base URLs.
   */
  protected function processBaseUrls(array $base_urls) {
    $processed_urls = [];
    foreach ($base_urls as $base_url) {
      $trimmed_url = rtrim($base_url, '/');
      if (!empty($trimmed_url)) {
        $processed_urls[] = $trimmed_url;
      }
    }
    return $processed_urls;
  }

}
