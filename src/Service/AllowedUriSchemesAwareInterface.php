<?php

namespace Drupal\purge_queuer_file_urls\Service;

interface AllowedUriSchemesAwareInterface {

  public function setAllowedUriSchemes(array $allowed_uri_schemes): void;

}
