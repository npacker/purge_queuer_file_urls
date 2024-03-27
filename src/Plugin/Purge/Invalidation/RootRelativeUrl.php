<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

/**
 * Describes URL based invalidation.
 *
 * @PurgeInvalidation(
 *   id = "rootrelativeurl",
 *   label = @Translation("Url (root-relative)"),
 *   description = @Translation("Invalidates by root-relative URL."),
 *   examples = {"/file/handle.ext"},
 *   expression_required = TRUE,
 *   expression_can_be_empty = FALSE
 * )
 */
class RootRelativeUrl extends UrlBase {

  /**
   * {@inheritdoc}
   */
  protected $absolute = FALSE;

}
