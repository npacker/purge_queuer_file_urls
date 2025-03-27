<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\FileUrlExpression;

/**
 * Base plugin class for URL expression strategies.
 *
 * URL expressions are preserved as objects until they are converted to a string
 * during the invalidation queuing phase. The distinction between the
 * 'relativeurl' and 'absoluteurl' plugin definitions is that they indicate
 * which invalidation type to request during queueing, which in turn determines
 * whether an absolute or relative URL invalidation is generated.
 */
abstract class UrlExpressionStrategyBase extends ExpressionStrategyBase implements FileExpressionStrategyInterface, ImageExpressionStrategyInterface, DerivativeExpressionStrategyInterface {

  /**
   * {@inheritdoc}
   */
  public function generateFileExpression(FileInterface $file) {
    return new FileUrlExpression($this->pluginId, $this->fileUrlGenerator->generate($file->getFileUri()));
  }

  /**
   * {@inheritdoc}
   */
  public function generateImageExpression(FileInterface $image) {
    $styles = ImageStyle::loadMultiple();
    foreach ($styles as $style) {
      yield new FileUrlExpression($this->pluginId, $this->fileUrlGenerator->generate($style->buildUri($image->getFileUri())));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateDerivativeExpression(ImageStyleInterface $style, string $path) {
    return new FileUrlExpression($this->pluginId, $this->fileUrlGenerator->generate($style->buildUri($path)));
  }

}
