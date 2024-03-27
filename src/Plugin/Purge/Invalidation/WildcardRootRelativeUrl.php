<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

/**
 * Describes URL based invalidation.
 *
 * @PurgeInvalidation(
 *   id = "wildcardrootrelativeurl",
 *   label = @Translation("Url (wildcard, root-relative)"),
 *   description = @Translation("Invalidates by wildcard root-relative URL."),
 *   examples = {"/file/path/*"},
 *   expression_required = TRUE,
 *   expression_can_be_empty = FALSE
 * )
 */
class WildcardRootRelativeUrl extends RootRelativeUrl {
  use WildcardExpressionTrait;

  /**
   * {@inheritdoc}
   */
  public function validateExpression() {
    parent::validateExpression();
    $this->validateWildcardExpression($this->expression);
  }

}
