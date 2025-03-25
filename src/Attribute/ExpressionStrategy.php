<?php

declare(strict_types=1);

namespace Drupal\purge_queuer_file_urls\Attribute;

use Attribute;
use Drupal\Component\Plugin\Attribute\Plugin;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines an ExpressionStrategy for plugin discovery.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ExpressionStrategy extends Plugin {

  /**
   * Constructs an ExpressionStrategy attribute.
   *
   * @param string $id
   *   The plugin ID.
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|null $label
   *   (optional) The human-readable name of the plugin.
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|null $description
   *   (optional) A short description of the plugin.
   * @param string[] $supports
   *   An array of supported invalidation cases.
   */
  public function __construct(
    public readonly string $id,
    public readonly ?TranslatableMarkup $label = NULL,
    public readonly ?TranslatableMarkup $description = NULL,
    public readonly array $supports = [],
  ) {}

}
