<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;

class ImageStyleUrlExpression implements FileUrlExpressionInterface {

  /**
   * The invalidation type plugin.
   *
   * @var string
   */
  protected $invalidationType;

  /**
   * The invalidation expression.
   *
   * @var string
   */
  protected $expression;

  /**
   * Creates a new FileUrlExpression object.
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

  /**
   * {@inheritdoc}
   */
  public function getInvalidation(InvalidationServiceInterface $purge_invalidation_factory) {
    return $purge_invalidation_factory->get($this->invalidationType, $this->expression);
  }

}
