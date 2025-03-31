<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Url;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;

class UrlExpression implements UrlExpressionInterface {

  /**
   * Creates a new UrlExpression object.
   *
   * @param string $invalidationType
   *   The invalidation plugin type associated with this expression.
   * @param \Drupal\Core\Url $expression
   *   The invalidation expression.
   */
  public function __construct(
    protected readonly string $invalidationType,
    protected readonly Url $expression
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getInvalidation(InvalidationsServiceInterface $purge_invalidation_factory) {
    return $purge_invalidation_factory->get($this->invalidationType, $this->expression);
  }

}
