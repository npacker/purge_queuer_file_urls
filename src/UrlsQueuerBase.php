<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Url;
use Drupal\purge\Plugin\Purge\Invalidation\Exception\TypeUnsupportedException;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;
use Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface;
use Drupal\purge\Plugin\Purge\Queuer\QueuerInterface;

abstract class UrlsQueuerBase implements FileUrlsQueuerInterface {

  /**
   * The purge invalidation factory service.
   *
   * @var \Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface
   */
  protected $purgeInvalidationFactory;

  /**
   * The purge queue service.
   *
   * @var \Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface
   */
  protected $purgeQueue;

  /**
   * The queuer plugin or NULL when disabled.
   *
   * @var \Drupal\purge\Plugin\Purge\Queuer\QueuerInterface
   */
  protected $purgeQueuerPlugin;

  /**
   * The invalidation type to set for invalidated file urls.
   *
   * @var string
   */
  protected $invalidationType;

  /**
   * A list of URLs that have already been invalidated this request.
   *
   * Used to prevent the invalidation of the same URL multiple times.
   *
   * @var array
   */
  protected $invalidatedUrls = [];

  /**
   * Constructs a new FileUrlsQueuer.
   *
   * @param \Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface $purge_invalidation_factory
   *   The purge invalidation factory service.
   * @param \Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface $purge_queue
   *   The purge queue service.
   * @param \Drupal\purge\Plugin\Queuer\Queuer $purge_queuer_plugin
   *   The purge queuer plugin.
   * @param string $invalidation_type
   *   The invalidation type.
   */
  public function __construct(InvalidationsServiceInterface $purge_invalidation_factory, QueueServiceInterface $purge_queue, QueuerInterface $purge_queuer_plugin, $invalidation_type) {
    $this->purgeInvalidationFactory = $purge_invalidation_factory;
    $this->purgeQueue = $purge_queue;
    $this->purgeQueuerPlugin = $purge_queuer_plugin;
    $this->invalidationType = $invalidation_type;
  }

  /**
   * {@inheritdoc}
   */
  public function invalidateUrls($urls) {
    if ($this->purgeQueuerPlugin) {
      $invalidations = [];
      /** @var \Drupal\Core\Url|string $url */
      foreach ($urls as $url) {
        try {
          /** @var \Drupal\purge\Plugin\Purge\Invalidation\InvalidationInterface $invalidation */
          $invalidation = $this->purgeInvalidationFactory->get($this->invalidationType, $url);
          $key = (string) $invalidation;
          if (empty($this->invalidatedUrls[$key])) {
            $invalidations[] = $invalidation;
            $this->invalidatedUrls[$key] = TRUE;
          }
        }
        catch (TypeUnsupportedException $e) {
          // A purger with URL support is not enabled.
          return;
        }
        catch (PluginNotFoundException $e) {
          // Uninstalling Purge or an otherwise stale plugin cache may prevent
          // the plugin from loading.
          return;
        }
      }
      if ($invalidations) {
        $this->purgeQueue->add($this->purgeQueuerPlugin, $invalidations);
      }
    }
  }

}
