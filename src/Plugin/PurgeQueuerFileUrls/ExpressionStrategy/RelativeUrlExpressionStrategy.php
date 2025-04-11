<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\file\FileInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Attribute\ExpressionStrategy;
use Drupal\purge_queuer_file_urls\UrlExpression;

#[ExpressionStrategy(
id: 'relativeurl',
  label: new TranslatableMarkup('Relative URL'),
  supports: [
    'derivative',
    'file',
  ]
)]
class RelativeUrlExpressionStrategy extends UrlExpressionStrategyBase {

  /**
   * {@inheritdoc}
   */
  public function generateFileExpression(FileInterface $file): \Generator {
    foreach ($this->fileUrlGenerator->generateRelative($file->getFileUri()) as $url) {
      yield new UrlExpression($this->pluginId, $url);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateDerivativeExpression(ImageStyleInterface $style, string $path): \Generator {
    foreach ($this->fileUrlGenerator->generateRelative($style->buildUri($path)) as $url) {
      yield new UrlExpression($this->pluginId, $url);
    }
  }

}
