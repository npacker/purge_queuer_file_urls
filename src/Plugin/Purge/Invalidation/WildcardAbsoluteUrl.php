<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

/**
 * Describes URL based invalidation.
 *
 * @PurgeInvalidation(
 *   id = "wildcardabsoluteurl",
 *   label = @Translation("Url (wildcard, absolute)"),
 *   description = @Translation("Invalidates by wildcard absolute URL."),
 *   examples = {"http://www.site.com/file/path/*"},
 *   expression_required = TRUE,
 *   expression_can_be_empty = FALSE,
 * )
 */
class WildcardAbsoluteUrl extends AbsoluteUrl {
  use WildcardExpressionTrait;

  /**
   * {@inheritdoc}
   */
  public function validateExpression() {
    parent::validateExpression();
    $this->validateWildcardExpression($this->expression);
  }

}
