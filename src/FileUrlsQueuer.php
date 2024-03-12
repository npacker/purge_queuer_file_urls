<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;
use Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface;
use Drupal\purge\Plugin\Purge\Queuer\QueuersServiceInterface;

/**
 * Queues file urls for cache invalidation.
 */
class FileUrlsQueuer extends UrlsQueuerBase {

  /**
   * Factory method for the FileUrlsQueuer.
   *
   * @param \Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface $purge_invalidation_factory
   *   The purge invalidation factory service.
   * @param \Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface $purge_queue
   *   The purge queue service.
   * @param \Drupal\purge\Plugin\Queuer\QueuersServiceInterface $purge_queuers
   *   The purge queuers service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public static function create(InvalidationsServiceInterface $purge_invalidation_factory, QueueServiceInterface $purge_queue, QueuersServiceInterface $purge_queuers, ConfigFactoryInterface $config_factory) {
    $purge_queuer_plugin = $purge_queuers->get('files');
    $config = $config_factory->get('purge_queuer_file_urls.settings');
    $absolute_urls = $config->get('absolute_urls') ?? FALSE;
    $invalidation_type = $absolute_urls ? 'absoluteurl' : 'rootrelativeurl';
    return new static(
      $purge_invalidation_factory,
      $purge_queue,
      $purge_queuer_plugin,
      $invalidation_type
    );
  }

}
