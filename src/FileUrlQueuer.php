<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;
use Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface;
use Drupal\purge\Plugin\Purge\Queuer\QueuersServiceInterface;

/**
 * Queues file urls for cache invalidation.
 */
class FileUrlQueuer extends UrlQueuerBase {

  /**
   * Factory method for the FileUrlQueuer.
   *
   * @param \Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface $purge_invalidation_factory
   *   The purge invalidation factory service.
   * @param \Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface $purge_queue
   *   The purge queue service.
   * @param \Drupal\purge\Plugin\Queuer\QueuersServiceInterface $purge_queuers
   *   The purge queuers service.
   */
  public static function create(InvalidationsServiceInterface $purge_invalidation_factory, QueueServiceInterface $purge_queue, QueuersServiceInterface $purge_queuers) {
    $purge_queuer_plugin = $purge_queuers->get('files');
    return new static(
      $purge_invalidation_factory,
      $purge_queue,
      $purge_queuer_plugin
    );
  }

}
