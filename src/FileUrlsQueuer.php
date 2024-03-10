<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Logger\LoggerChannelTrait;
use Drupal\purge\Plugin\Purge\Invalidation\Exception\TypeUnsupportedException;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;
use Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface;
use Drupal\purge\Plugin\Purge\Queuer\QueuersServiceInterface;

/**
 * Queues file urls for cache invalidation.
 */
class FileUrlsQueuer implements FileUrlsQueuerInterface {
  use LoggerChannelTrait;

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
   * A list of URLs that have already been invalidated this request.
   *
   * Used to prevent the invalidation of the same URL multiple times.
   *
   * @var array
   */
  protected $invalidatedUrls = [];

  /**
   * Constructs a new FileURlsQueuer object.
   *
   * @param \Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface $purge_invalidation_factory
   *   The purge invalidation factory service.
   * @param \Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface $purge_queue
   *   The purge queue service.
   * @param \Drupal\purge\Plugin\Queuer\QueuersServiceInterface $purge_queuers
   *   The purge queuers service.
   */
  public function __construct(InvalidationsServiceInterface $purge_invalidation_factory, QueueServiceInterface $purge_queue, QueuersServiceInterface $purge_queuers) {
    $this->purgeInvalidationFactory = $purge_invalidation_factory;
    $this->purgeQueue = $purge_queue;
    $this->purgeQueuerPlugin = $purge_queuers->get('fileurls');
  }

  /**
   * {@inheritdoc}
   */
  public function invalidateUrls(array $urls, bool $absolute = FALSE) {
    if ($this->purgeQueuerPlugin) {
      $invalidations = [];
      /** @var \Drupal\Core\Url $url */
      foreach ($urls as $url) {
        if (isset($invalidatedUrls[$url->toString()])) {
          continue;
        }
        $invalidation_type = $absolute ? 'absoluteurl' : 'rootrelativeurl';
        try {
          $invalidations[] = $this->purgeInvalidationFactory->get($invalidation_type, $url);
          $this->invalidatedUrls[$url->toString()] = TRUE;
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
