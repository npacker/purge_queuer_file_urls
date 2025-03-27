<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Attribute\ExpressionStrategy;
use Drupal\purge_queuer_file_urls\StringExpression;
use Drupal\purge_queuer_file_urls\UrlExpressionInterface;

#[ExpressionStrategy(
  id: 'wildcard',
  label: new TranslatableMarkup('Wildcard'),
  supports: [
    'image',
    'style',
  ],
)]
class WildcardExpressionStrategy extends ExpressionStrategyBase implements ImageExpressionStrategyInterface, StyleExpressionStrategyInterface {

  /**
   * {@inheritdoc}
   */
  public function generateImageExpression(FileInterface $image): \Generator {
    $image_uri = $image->getFileUri();
    $styles = ImageStyle::loadMultiple();
    foreach ($styles as $style) {
      $style_url = $this->fileUrlGenerator->generate($style->buildUri($image_uri));
      $haystack = $style_url->setAbsolute($this->absoluteUrls)->toString();
      $replacement = '*';
      $needle = preg_quote($style->id(), '/');
      yield new StringExpression($this->pluginId, preg_replace('/(?<=\/)' . $needle . '(?=\/)/', $replacement, $haystack));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateStyleExpression(ImageStyleInterface $style): UrlExpressionInterface {
    $url = $this->fileUrlGenerator->generate($style->buildUri(''));
    return new StringExpression($this->pluginId, $url->setAbsolute($this->absoluteUrls)->toString() . '\/*');
  }

}

