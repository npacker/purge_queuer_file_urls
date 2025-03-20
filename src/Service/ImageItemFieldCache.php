<?php

namespace Drupal\purge_queuer_file_urls\Service;

use Drupal\file\Plugin\Field\FieldType\ImageItem;

/**
 * Caching service for \Drupal\file\Plugin\Field\FileItem fields.
 */
class ImageItemFieldCache extends FieldCacheBase {

  /**
   * {@inheritdoc}
   */
  protected $type = ImageItem::class;

}
