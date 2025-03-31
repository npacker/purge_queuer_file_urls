<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;
use Drupal\purge\Plugin\Purge\Invalidation\Exception\InvalidExpressionException;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationBase;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationInterface;

/**
 * Base class for URL-based cache invalidations.
 *
 * This abstract class provides a foundation for implementing cache
 * invalidation strategies that are based on URLs. It includes methods to
 * validate and represent URLs as expressions.
 */
abstract class UrlBase extends InvalidationBase implements InvalidationInterface {

  /**
   * Whether the URL should be absolute or relative.
   *
   * @var bool
   */
  protected $absolute;

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    if ($this->expression instanceof Url) {
      return $this->expression->toString();
    }
    else {
      return (string) $this->expression;
    }
  }

  /**
   * {@inheritdoc}
   *
   */
  public function getExpression() {
    return (string) $this;
  }

  /**
   * {@inheritdoc}
   */
  public function validateExpression() {
    parent::validateExpression();
    try {
      $expression = $this->getExpression();
      if (!UrlHelper::isValid($expression, $this->absolute)) {
        throw new InvalidExpressionException('Invalid URL.');
      }
    }
    catch (\Exception $e) {
      throw new InvalidExpressionException($e->getMessage(), $e->getCode(), $e);
    }
  }

}
