<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Queuer;

use Drupal\purge\Plugin\Purge\Queuer\QueuerBase;
use Drupal\purge\Plugin\Purge\Queuer\QueuerInterface;

/**
 * Queues file URLs.
 *
 * @PurgeQueuer(
 *   id = "files",
 *   label = @Translation("Files Queuer"),
 *   description = @Translation("Queues file URLs on entity update."),
 *   enable_by_default = true,
 *   configform = "\Drupal\purge_queuer_file_urls\Form\FilesQueuerConfigForm",
 * )
 */
class FilesQueuer extends QueuerBase implements QueuerInterface {}
