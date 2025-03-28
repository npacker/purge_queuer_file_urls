<?php

namespace Drupal\purge_queuer_file_urls\Service;

interface StreamWrapperFilterServiceInterface {

  public function filterFiles(array $files = []): array;

  public function filterUris(array $uris = []): array;

}
