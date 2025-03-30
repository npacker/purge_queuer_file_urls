<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Attribute\ExpressionStrategy;
use Drupal\purge_queuer_file_urls\StringExpression;
use Drupal\purge_queuer_file_urls\UrlExpressionInterface;

#[ExpressionStrategy(
  id: 'wildcard',
  label: new TranslatableMarkup('Wildcard'),
  supports: [
    'derivative',
    'style',
  ],
)]
class WildcardExpressionStrategy extends ExpressionStrategyBase implements DerivativeExpressionStrategyInterface, StyleExpressionStrategyInterface {

  public function generateDerivativeExpression(ImageStyleInterface $style, string $path): UrlExpressionInterface {
    $style_url = $this->fileUrlGenerator->generate($style->buildUri($path));
    $haystack = $style_url->toString();
    $replacement = '*';
    $needle = preg_quote($style->id(), '/');
    return new StringExpression($this->pluginId, preg_replace('/(?<=\/)' . $needle . '(?=\/)/', $replacement, $haystack));
  }

  /**
   * {@inheritdoc}
   */
  public function generateStyleExpression(ImageStyleInterface $style): UrlExpressionInterface {
    $url = $this->fileUrlGenerator->generate($style->buildUri(''));
    return new StringExpression($this->pluginId, $url . '\/*');
  }

}

