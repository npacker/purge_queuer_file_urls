<?php

namespace Drupal\purge_queuer_file_urls;

interface ImageStyleAwareInterface {

  public function setImageStyles(array $image_styles): void;

}
