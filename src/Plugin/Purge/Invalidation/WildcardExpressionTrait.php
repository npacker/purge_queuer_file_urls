<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

use Drupal\purge\Plugin\Purge\Invalidation\Exception\InvalidExpressionException;

trait WildcardExpressionTrait {

  /**
   * Validate a wildcard expression.
   *
   * @param string $expression
   *  The expression to validate.
   */
  protected function validateWildcardExpression($expression) {
    if (strpos($expression, '*') === FALSE) {
      throw new InvalidExpressionException('Wildcard invalidations should contain an asterisk.');
    }
  }

}
