<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\image\ImageStyleInterface;

interface DerivativeExpressionStrategyInterface extends PluginInspectionInterface {

  public function generateDerivativeExpression(ImageStyleInterface $style, string $path);

}
