<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\purge\Plugin\Purge\Invalidation\Exception\TypeUnsupportedException;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;
use Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface;
use Drupal\purge\Plugin\Purge\Queuer\QueuerInterface;

/**
 * Base class for URL queuer classes.
 *
 * Implementations of this class will inject the dependencies required by the
 * specific queuer implementation. This includes the purge queuer plugin (to
 * determine if the queuer has been enabled by the user), any relevant settings,
 * and the invalidation type supported by the queuer.
 */
abstract class UrlQueuerBase implements UrlQueuerInterface {

  /**
   * A list of URLs that have already been invalidated this request.
   *
   * Used to prevent the invalidation of the same URL multiple times.
   *
   * @var bool[]
   */
  protected $invalidatedUrls = [];

  /**
   * Constructs a new FileUrlQueuer.
   *
   * @param \Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface $purgeInvalidationFactory
   *   The purge invalidation factory service.
   * @param \Drupal\purge\Plugin\Purge\Queue\QueueServiceInterface $purgeQueue
   *   The purge queue service.
   * @param \Drupal\purge\Plugin\Queuer\Queuer $purgeQueuerPlugin
   *   The purge queuer plugin.
   */
  public function __construct(
    protected readonly InvalidationsServiceInterface $purgeInvalidationFactory,
    protected readonly QueueServiceInterface $purgeQueue,
    protected readonly QueuerInterface $purgeQueuerPlugin,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function invalidateUrls(iterable $urls) {
    if ($this->purgeQueuerPlugin) {
      /** @var \Drupal\purge_queuer_file_urls\UrlExpressionInterface $expression */
      foreach ($urls as $expression) {
        try {
          /** @var \Drupal\purge\Plugin\Purge\Invalidation\InvalidationInterface $invalidation */
          $invalidation = $expression->getInvalidation($this->purgeInvalidationFactory);
          $key = (string) $invalidation;
          if (empty($this->invalidatedUrls[$key])) {
            $this->invalidatedUrls[$key] = TRUE;
            $this->purgeQueue->add($this->purgeQueuerPlugin, [$invalidation]);
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
    }
  }

}
