<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Queuer;

use Drupal\purge\Plugin\Purge\Queuer\QueuerBase;
use Drupal\purge\Plugin\Purge\Queuer\QueuerInterface;

/**
 * Queues file URLs.
 *
 * @PurgeQueuer(
 *   id = "imagestyles",
 *   label = @Translation("Image Styles Queuer"),
 *   description = @Translation("Queues image styles on image style flush."),
 *   enable_by_default = true,
 * )
 */
class ImageStylesQueuer extends QueuerBase implements QueuerInterface {}
