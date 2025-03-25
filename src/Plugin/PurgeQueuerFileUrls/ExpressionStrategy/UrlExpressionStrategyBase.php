<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\file\FileInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\image\ImageStyleInterface;

abstract class UrlExpressionStrategyBase extends ExpressionStrategyBase implements FileExpressionStrategyInterface, ImageExpressionStrategyInterface, DerivatriveExpressionStrategyInterface {

  public function generateFileExpression(FileInterface $file) {
    return $this->fileUrlGenerator->generate($file->getFileUri());
  }

  public function generateImageExpression(FileInterface $image) {
    $styles = ImageStyle::loadMultiple();
    foreach ($styles as $style) {
      yield $this->fileUrlGenerator->generate($style->buildUri($image->getFileUri()));
    }
  }

  public function generateDerivativeExpresison(ImageStyleInterface $style, string $path) {
    return $this->fileUrlGenerator->generate($style->buildUri($path));
  }

}
