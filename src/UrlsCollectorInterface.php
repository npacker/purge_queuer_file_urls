<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityInterface;

interface UrlsCollectorInterface {

  /**
   * Collect file URLs by traversing fields of the given entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The updated entity.
   * @return \Drupal\Core\Url[]
   *   The array of URL objects.
   */
  public function collect(EntityInterface $entity);

}
