<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

/**
 * Describes URL based invalidation.
 *
 * @PurgeInvalidation(
 *   id = "baserelativeurl",
 *   label = @Translation("Url (base-relative)"),
 *   description = @Translation("Invalidates by base-relative URL."),
 *   examples = {"/file/handle.ext"},
 *   expression_required = TRUE,
 *   expression_can_be_empty = FALSE,
 * )
 */
class BaseRelativeUrl extends UrlBase {

  /**
   * {@inheritdoc}
   */
  protected $absolute = FALSE;

}
