<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Config\ConfigFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseUrlsProvider implements BaseUrlsProviderInterface {

  /**
   * Constructs a new BaseUrlsProvider instance.
   *
   * @param bool $includeSiteBaseUrl
   *   (optional) Whether to include the site base URL. Defaults to TRUE.
   * @param array $baseUrls
   *   (optional) Additional base URLs to include.
   * @param string $currentBaseUrl
   *   The current base URL.
   */
  public function __construct(
    protected readonly ?bool $includeSiteBaseUrl = TRUE,
    protected readonly ?array $baseUrls = [],
    protected readonly string $currentBaseUrl,
  ) {}

  /**
   * Creates a new BaseUrlsProvider instance.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The configuration factory.
   *
   * @return \Drupal\purge_queuer_file_urls\Service\BaseUrlsProvider
   *   A new BaseUrlsProvider instance.
   */
  public static function create(RequestStack $request_stack, ConfigFactory $config_factory) {
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    $include_site_base_url = $config->get('include_site_base_url');
    $base_urls = $config->get('base_urls');
    $current_base_url = $request_stack->getCurrentRequest()->getSchemeAndHttpHost();
    return new static(
      $include_site_base_url,
      $base_urls,
      $current_base_url,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function iterateBaseUrls(): \Generator {
    if ($this->includeSiteBaseUrl) {
      yield $this->currentBaseUrl;
    }
    foreach ($this->baseUrls as $base_url) {
      yield $base_url;
    }
  }

}