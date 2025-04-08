<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\file\FileInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\UrlExpression;

/**
 * Base plugin class for URL expression strategies.
 *
 * URL expressions are preserved as objects until they are converted to a string
 * during the invalidation queuing phase. The distinction between the
 * 'relativeurl' and 'absoluteurl' plugin definitions is that they indicate
 * which invalidation type to request during queueing, which in turn determines
 * whether an absolute or relative URL invalidation is generated.
 */
abstract class UrlExpressionStrategyBase extends ExpressionStrategyBase implements FileExpressionStrategyInterface, DerivativeExpressionStrategyInterface {

  /**
   * {@inheritdoc}
   */
  public function generateFileExpression(FileInterface $file): \Generator {
    foreach ($this->fileUrlGenerator->generate($file->getFileUri()) as $url) {
      yield new UrlExpression($this->pluginId, $url);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateDerivativeExpression(ImageStyleInterface $style, string $path): \Generator {
    foreach ($this->fileUrlGenerator->generate($style->buildUri($path)) as $url) {
      yield new UrlExpression($this->pluginId, $url);
    }
  }

}
