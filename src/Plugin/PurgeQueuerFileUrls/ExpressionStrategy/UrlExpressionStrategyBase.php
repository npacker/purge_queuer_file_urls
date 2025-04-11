<?php

namespace Drupal\purge_queuer_file_urls\Plugin\PurgeQueuerFileUrls\ExpressionStrategy;

/**
 * Base plugin class for URL expression strategies.
 *
 * URL expressions are preserved as objects until they are converted to a string
 * during the invalidation queuing phase. The distinction between the
 * 'relativeurl' and 'absoluteurl' plugin definitions is that they indicate
 * which invalidation type to request during queueing, which in turn determines
 * whether an absolute or relative URL invalidation is generated.
 */
abstract class UrlExpressionStrategyBase extends ExpressionStrategyBase implements FileExpressionStrategyInterface, DerivativeExpressionStrategyInterface {}
