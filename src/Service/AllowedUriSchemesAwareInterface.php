<?php

namespace Drupal\purge_queuer_file_urls\Service;

/**
 * Defines an interface for file scheme setting injection.
 *
 * This gives classes the ability to interface with the allowed uri schemes
 * configurator.
 */
interface AllowedUriSchemesAwareInterface {

  /**
   * Set the allowed URI schemes.
   *
   * @param string[] $allowed_uri_schemes
   *   An array of the allowed URI schemes.
   */
  public function setAllowedUriSchemes(array $allowed_uri_schemes): void;

}
