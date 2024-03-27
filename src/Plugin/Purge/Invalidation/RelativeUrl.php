<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

/**
 * Describes URL based invalidation.
 *
 * @PurgeInvalidation(
 *   id = "relativeurl",
 *   label = @Translation("Url (relative)"),
 *   description = @Translation("Invalidates by relative URL."),
 *   examples = {"/file/handle.ext"},
 *   expression_required = TRUE,
 *   expression_can_be_empty = FALSE
 * )
 */
class RelativeUrl extends UrlBase {

  /**
   * {@inheritdoc}
   */
  protected $absolute = FALSE;

}
