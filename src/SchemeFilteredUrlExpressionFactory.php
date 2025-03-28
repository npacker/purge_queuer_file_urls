<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\File\FileInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Service\StreamWrapperFilterServiceInterface;

/**
 * Decorates UrlExpressionFactory objects.
 *
 * Filters out files and image style paths via the stream wrapper filter
 * service.
 */
class SchemeFilteredUrlExpressionFactory implements UrlExpressionFactoryInterface {

  /**
   * Constructs a new SchemeFilteredUrlExpressionFactory object.
   *
   * @param \Drupal\purge_queuer_file_urls\UrlExpressionFactoryInterface $inner
   *   The inner URL expression factory.
   * @param \Drupal\purge_queuer_file_urls\Service\StreamWrapperFilterServiceInterface $streamWrapperFilterService
   *   The stream wrapper filter service.
   */
  public function __construct(
    protected readonly UrlExpressionFactoryInterface $inner,
    protected readonly StreamWrapperFilterServiceInterface $streamWrapperFilterService,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function generateFromFile(FileInterface $file): \Generator {
    if ($this->streamWrapperFilterService->filterFiles([$file])) {
      yield from $this->inner->generateFromFile(array_pop($filtered_files));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function generateFromStyle(ImageStyleInterface $style, string $path = ''): \Generator {
    if ($this->streamWrapperFilterService->filterUris([$path])) {
      yield from $this->inner->generateFromStyle($style, $path);
    }
  }

}
