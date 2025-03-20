<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\file\Plugin\Field\FieldType\FileItem;

/**
 * Caching service for \Drupal\file\Plugin\Field\FileItem fields.
 */
class FileItemFieldCache extends FieldCacheBase {

  /**
   * {@inheritdoc}
   */
  protected $type = FileItem::class;

}
