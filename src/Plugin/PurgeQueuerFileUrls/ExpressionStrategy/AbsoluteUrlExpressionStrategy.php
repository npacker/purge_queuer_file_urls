<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\purge_queuer_file_urls\Attribute\ExpressionStrategy;

#[ExpressionStrategy(
  id: 'absoluteurl',
  label: new TranslatableMarkup('Absolute URL'),
  supports: [
    'derivative',
    'file',
    'image',
  ]
)]
class AbsoluteUrlExpressionStrategy extends UrlExpressionStrategyBase {}
