<?php

namespace Drupal\purge_queuer_file_urls\Service;

interface AbsoluteUrlsAwareInterface {

  public function setAbsolute(bool $absolute): void;

}
