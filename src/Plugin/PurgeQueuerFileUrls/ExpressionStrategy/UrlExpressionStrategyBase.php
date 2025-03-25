<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\ImageStyleInterface;

/**
 * Base plugin class for URL expression strategies.
 */
abstract class UrlExpressionStrategyBase extends ExpressionStrategyBase implements FileExpressionStrategyInterface, ImageExpressionStrategyInterface, DerivativeExpressionStrategyInterface {

  /**
   * {@inheritdoc}
   */
  public function generateFileExpression(FileInterface $file) {
    return $this->fileUrlGenerator->generate($file->getFileUri());
  }

  /**
   * {@inheritdoc}
   */
  public function generateImageExpression(FileInterface $image) {
    $styles = ImageStyle::loadMultiple();
    foreach ($styles as $style) {
      yield $this->fileUrlGenerator->generate($style->buildUri($image->getFileUri()));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateDerivativeExpression(ImageStyleInterface $style, string $path) {
    return $this->fileUrlGenerator->generate($style->buildUri($path));
  }

}
