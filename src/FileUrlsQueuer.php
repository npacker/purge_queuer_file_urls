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
  public function invalidateUrls(array $urls) {
    if ($this->purgeQueuerPlugin) {
      $invalidations = [];
      foreach ($urls as $url) {
        try {
          $invalidations[] = $this->purgeInvalidationFactory->get('url', $url);
        }
        catch (TypeUnsupportedException $e) {
          // A purger with URL support is not enabled.
          return;
        }
        catch (PluginNotFoundException $e) {
          // Uninstalling Purge may cause transient spurious attempts to load missing
          // plugins.
          return;
        }
      }
      if ($invalidations) {
        $this->purgeQueue->add($this->purgeQueuerPlugin, $invalidations);
      }
    }
  }

}
