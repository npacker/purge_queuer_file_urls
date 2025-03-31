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
   * @param string $invalidationType
   *   The invalidation plugin type associated with this expression.
   * @param string $expression
   *   The invalidation expression.
   */
  public function __construct(
    protected readonly string $invalidationType,
    protected readonly string $expression,
  ) {}

}
