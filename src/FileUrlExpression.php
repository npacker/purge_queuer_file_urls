<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Url;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationsServiceInterface;

class FileUrlExpression implements UrlExpressionInterface {

  /**
   * The invalidation type plugin.
   *
   * @var string
   */
  protected $invalidationType;

  /**
   * The invalidation expression.
   *
   * @var \Drupal\Core\Url
   */
  protected $expression;

  /**
   * Creates a new FileUrlExpression object.
   *
   * @param string $invalidation_type
   *   The invalidation plugin type associated with this expression.
   * @param \Drupal\Core\Url $expression
   *   The invalidation expression.
   */
  public function __construct(string $invalidation_type, Url $expression) {
    $this->invalidatinoType = $invalidation_type;
    $this->expression = $expression;
  }

  /**
   * {@inheritdoc}
   */
  public function getInvalidation(InvalidationsServiceInterface $purge_invalidation_factory) {
    return $purge_invalidation_factory->get($this->invalidationType, $this->expression);
  }

}
