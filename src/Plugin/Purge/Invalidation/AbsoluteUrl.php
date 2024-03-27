<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

/**
 * Describes URL based invalidation.
 *
 * @PurgeInvalidation(
 *   id = "absoluteurl",
 *   label = @Translation("Url (absolute)"),
 *   description = @Translation("Invalidates by absolute URL."),
 *   examples = {"http://www.site.com/file/handle.ext"},
 *   expression_required = TRUE,
 *   expression_can_be_empty = FALSE
 * )
 */
class AbsoluteUrl extends UrlBase {

  /**
   * {@inheritdoc}
   */
  protected $absolute = TRUE;

}
