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
    return $this->expression->setAbsolute($this->absolute)->toString();
  }

  /**
   * {@inheritdoc}
   */
  public function validateExpression() {
    parent::validateExpression();
    if (!($this->expression instanceof Url)) {
      throw new InvalidExpressionException('Expression must be an instance of \Drupal\Core\Url.');
    }
    try {
      $this->expression->toString();
    }
    catch (Exception $e) {
      throw new InvalidExpressionException($e->getMessage(), $e->getCode(), $e);
    }
  }

}
