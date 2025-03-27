<?php

namespace Drupal\purge_queuer_file_urls;

/**
 * Defines a string expression class.
 *
 * This class handles all cases where the invalidation expression starts as a
 * string, such as wildcard or regular expressions.
 */
class StringExpression extends UrlExpression {

  /**
   * Creates a new StringExpression object.
   *
   * @param string $invalidation_type
   *   The invalidation plugin type associated with this expression.
   * @param string $expression
   *   The invalidation expression.
   */
  public function __construct(string $invalidation_type, string $expression) {
    $this->invalidationType = $invalidation_type;
    $this->expression = $expression;
  }

}
