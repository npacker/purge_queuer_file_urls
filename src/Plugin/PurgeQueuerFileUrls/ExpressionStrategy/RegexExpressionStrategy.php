<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Attribute\ExpressionStrategy;
use Drupal\purge_queuer_file_urls\StringExpression;
use Drupal\purge_queuer_file_urls\UrlExpressionInterface;

#[ExpressionStrategy(
  id: 'regex',
  label: new TranslatableMarkup('Regular Expression'),
  supports: [
    'derivative',
    'style',
  ],
)]
class RegexExpressionStrategy extends ExpressionStrategyBase implements DerivativeExpressionStrategyInterface, StyleExpressionStrategyInterface {

  /**
   * {@inheritdoc}
   */
  public function generateDerivativeExpression(ImageStyleInterface $style, string $path): UrlExpressionInterface {
    $style_url = $this->fileUrlGenerator->generate($style->buildUri($path));
    $haystack = $style_url->setAbsolute($this->absoluteUrls)->toString();
    $replacement = '.*';
    $needle = preg_quote($style->id(), '/');
    return new StringExpression($this->pluginId, '^' . preg_replace('/(?<=\/)' . $needle . '(?=\/)/', $replacement, $haystack) . '$');
  }

  /**
   * {@inheritdoc}
   */
  public function generateStyleExpression(ImageStyleInterface $style): UrlExpressionInterface {
    $url = $this->fileUrlGenerator->generate($style->buildUri(''));
    return new StringExpression($this->pluginId, '^' . $url->setAbsolute($this->absoluteUrls)->toString() . '\/.*$');
  }

}
