<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\purge_queuer_file_urls\Attribute\ExpressionStrategy;

#[ExpressionStrategy(
  id: 'relativeurl',
  label: new TranslatableMarkup('Relative URL'),
  supports: [
    'derivative',
    'file',
    'image',
  ]
)]
class RelativeUrlExpressionStrategy extends UrlExpressionStrategyBase {}
