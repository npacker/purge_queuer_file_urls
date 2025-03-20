<?php

namespace Drupal\purge_queuer_file_urls;

use Drupal\Core\Entity\EntityInterface;

/**
 * Definines an interface for URL collector classes that process an entity for
 * URLs to invalidate.
 */
interface UrlCollectorInterface {

  /**
   * Collect URLs by traversing fields of the given entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The updated entity.
   *
   * @return \Generator<Drupal\purge_queuer_file_urls\FileInvalidationExpressionInterface>
   *   An iterable of URLs collected from the entity.
   */
  public function collect(EntityInterface $entity);

}
