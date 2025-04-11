<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\file\FileInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Attribute\ExpressionStrategy;
use Drupal\purge_queuer_file_urls\UrlExpression;

#[ExpressionStrategy(
  id: 'absoluteurl',
  label: new TranslatableMarkup('Absolute URL'),
  supports: [
    'derivative',
    'file',
  ]
)]
class AbsoluteUrlExpressionStrategy extends UrlExpressionStrategyBase {

  /**
   * {@inheritdoc}
   */
  public function generateFileExpression(FileInterface $file): \Generator {
    foreach ($this->fileUrlGenerator->generateAbsolute($file->getFileUri()) as $url) {
      yield new UrlExpression($this->pluginId, $url);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateDerivativeExpression(ImageStyleInterface $style, string $path): \Generator {
    foreach ($this->fileUrlGenerator->generateAbsolute($style->buildUri($path)) as $url) {
      yield new UrlExpression($this->pluginId, $url);
    }
  }

}
