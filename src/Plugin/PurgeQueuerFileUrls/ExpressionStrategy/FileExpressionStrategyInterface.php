<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\file\FileInterface;

interface FileExpressionStrategyInterface extends ExpressionStrategyInterface {

  public function generateFileExpression(FileInterface $file);

}
