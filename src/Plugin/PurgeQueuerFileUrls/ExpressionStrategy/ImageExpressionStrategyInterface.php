<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\file\FileInterface;

interface ImageExpressionStrategyInterface extends PluginInspectionInterface {

  public function generateImageExpression(FileInterface $image);

}
