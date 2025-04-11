<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Url;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;

/**
 * Represents a URL expression used for cache invalidation.
 *
 * This class encapsulates the logic necessary to create and retrieve an
 * invalidation object based on a given URL and invalidation type.
 */
class UrlExpression implements UrlExpressionInterface {

  /**
   * Creates a new UrlExpression object.
   *
   * @param string $invalidationType
   *   The invalidation plugin type associated with this expression.
   * @param mixed $expression
   *   The URL expression to be invalidated.
   */
  public function __construct(
    protected readonly string $invalidationType,
    protected readonly mixed $expression
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getInvalidation(InvalidationsServiceInterface $purge_invalidation_factory) {
    return $purge_invalidation_factory->get($this->invalidationType, $this->expression);
  }

}
