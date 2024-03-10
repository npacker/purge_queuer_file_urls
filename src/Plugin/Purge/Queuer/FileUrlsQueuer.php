<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Queuer;

use Drupal\purge\Plugin\Purge\Queuer\QueuerBase;
use Drupal\purge\Plugin\Purge\Queuer\QueuerInterface;

/**
 * Queues file URLs.
 *
 * @PurgeQueuer(
 *   id = "fileurls",
 *   label = @Translation("File URLs Queuer"),
 *   description = @Translation("Queues File URLs."),
 *   enable_by_default = true,
 *   configform = "\Drupal\purge_queuer_file_urls\Form\FileUrlsQueuerConfigForm",
 * )
 */
class FileUrlsQueuer extends QueuerBase implements QueuerInterface {}
