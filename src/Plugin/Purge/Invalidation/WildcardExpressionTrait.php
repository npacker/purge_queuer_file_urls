<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

use Drupal\purge\Plugin\Purge\Invalidation\Exception\InvalidExpressionException;

trait WildcardExpressionTrait {

  /**
   * {@inheritdoc}
   */
  public function validateExpression() {
    parent::validateExpression();
    if (strpos($expression, '*') === FALSE) {
      throw new InvalidExpressionException('Wildcard invalidations must contain an asterisk.');
    }
  }

}
