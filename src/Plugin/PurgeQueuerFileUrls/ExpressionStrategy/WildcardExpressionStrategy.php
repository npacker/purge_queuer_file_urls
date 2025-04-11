<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Attribute\ExpressionStrategy;
use Drupal\purge_queuer_file_urls\UrlExpression;

#[ExpressionStrategy(
  id: 'wildcard',
  label: new TranslatableMarkup('Wildcard'),
  supports: [
    'derivative',
    'style',
  ],
)]
class WildcardExpressionStrategy extends ExpressionStrategyBase implements DerivativeExpressionStrategyInterface, StyleExpressionStrategyInterface {

  /**
   * {@inheritdoc}
   */
  public function generateDerivativeExpression(ImageStyleInterface $style, string $path): \Generator {
    foreach ($this->fileUrlGenerator->generate($style->buildUri($path)) as $style_url) {
      $haystack = $style_url->toString();
      $replacement = '*';
      $needle = preg_quote($style->id(), '/');
      yield new UrlExpression($this->pluginId, preg_replace('/(?<=\/)' . $needle . '(?=\/)/', $replacement, $haystack));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateStyleExpression(ImageStyleInterface $style): \Generator {
    foreach ($this->fileUrlGenerator->generate($style->buildUri('')) as $url) {
      yield new UrlExpression($this->pluginId, $url . '\/*');
    }
  }

}
