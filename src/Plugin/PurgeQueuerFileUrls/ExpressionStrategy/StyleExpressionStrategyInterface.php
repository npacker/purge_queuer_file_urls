<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\image\ImageStyleInterface;

interface StyleExpressionStrategyInterface extends PluginInspectionInterface {

  public function generateStyleExpression(ImageStyleInterface $style);

}
