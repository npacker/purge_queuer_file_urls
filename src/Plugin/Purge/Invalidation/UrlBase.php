<?php

namespace Drupal\purge_queuer_file_urls\Plugin\Purge\Invalidation;

use Drupal\Core\Url;
use Drupal\purge\Plugin\Purge\Invalidation\Exception\InvalidExpressionException;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationBase;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationInterface;

abstract class UrlBase extends InvalidationBase implements InvalidationInterface {

  /**
   * Whether the URL should be absolute or relative.
   *
   * @var bool
   */
  protected $absolute;

  /**
   * Convert the internal \Drupal\Core\Url object to a string.
   *
   * @return string
   */
  public function __toString() {
    if ($this->expression instanceof Url) {
      return $this->expression->setAbsolute($this->absolute)->toString();
    }
    else {
      return (string) $this->expression;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateExpression() {
    parent::validateExpression();
    if ($this->expression instanceof Url) {
      try {
        $this->expression->toString();
      }
      catch (\Exception $e) {
        throw new InvalidExpressionException($e->getMessage(), $e->getCode(), $e);
      }
    }
  }

}
