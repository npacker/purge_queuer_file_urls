<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\Core\Config\ConfigFactory;
use Symfony\Component\HttpFoundation\RequestStack;

class BaseUrlsProvider implements BaseUrlsProviderInterface {

  /**
   * Constructs a new BaseUrlsProvider instance.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   * @param bool $includeSiteBaseUrl
   *   (optional) Whether to include the site base URL. Defaults to TRUE.
   * @param array $baseUrls
   *   (optional) Additional base URLs to include.
   */
  public function __construct(
    protected readonly RequestStack $requestStack,
    protected readonly ?bool $includeSiteBaseUrl = TRUE,
    protected readonly ?array $baseUrls = [],
  ) {}

  public static function create(RequestStack $request_stack, ConfigFactory $config_factory) {
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    $include_site_base_url = $config->get('include_site_base_url');
    $base_urls = $config->get('base_urls');
    return new static(
      $request_stack,
      $include_site_base_url,
      $base_urls,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function iterateBaseUrls(): \Generator {
    if ($this->includeSiteBaseUrl) {
      $current_base_url = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
      yield $current_base_url;
    }
    foreach ($this->baseUrls as $base_url) {
      yield $base_url;
    }
  }

}