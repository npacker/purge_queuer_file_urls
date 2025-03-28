<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\File\FileInterface;
use Drupal\image\ImageStyleInterface;
use Drupal\purge_queuer_file_urls\Service\StreamWrapperFilterServiceInterface;

class SchemeFilteredUrlExpressionFactory implements UrlExpressionFactoryInterface {

  public function __construct(
    protected readonly UrlExpressionFactoryInterface $inner,
    protected readonly StreamWrapperFilterServiceInterface $streamWrapperFilterService,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function generateFromFile(FileInterface $file): \Generator {
    $filtered_files = $this->streamWrapperFilterService->filterFiles([$file]);
    if ($filtered_files) {
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
