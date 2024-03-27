<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

/**
 * Describes URL based invalidation.
 *
 * @PurgeInvalidation(
 *   id = "wildcardrelativeurl",
 *   label = @Translation("Url (wildcard, relative)"),
 *   description = @Translation("Invalidates by wildcard relative URL."),
 *   examples = {"/file/path/*"},
 *   expression_required = TRUE,
 *   expression_can_be_empty = FALSE
 * )
 */
class WildcardRelativeUrl extends RelativeUrl {
  use WildcardExpressionTrait;

  /**
   * {@inheritdoc}
   */
  public function validateExpression() {
    parent::validateExpression();
    $this->validateWildcardExpression($this->expression);
  }

}
