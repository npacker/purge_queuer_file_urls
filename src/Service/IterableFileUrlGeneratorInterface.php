<?php

namespace Drupal\purge_queuer_file_urls\Service;

/**
 * Interface for a file URL generator that provides generator methods for
 * creating file URLs.
 */
interface IterableFileUrlGeneratorInterface {

  /**
   * Generates web-accessible URL objects, pre-configured based on the value of
   * the $absolute parameter.
   *
   * @param string $uri
   *   The URI to a file for which we need an external URL, or the path to a
   *   shipped file.
   *
   * @return \Drupal\Core\Url
   *   A Url object with absolute URL. Use setAbsolute() on the Url object to
   *   change the final form of the URL before string conversion.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   *
   * @see \Drupal\purge_queuer_file_urls\Service\ConfigurationAwareFileUrlGenerator::$absolute.
   */
  public function generate(string $uri): \Generator;

  /**
   * Genreates absolute web-accessible URL objects.
   *
   * @param string $uri
   *   The URI to a file for which we need an external URL, or the path to a
   *   shipped file.
   *
   * @return \Drupal\Core\Url
   *   A Url object with absolute URL. Use setAbsolute() on the Url object to
   *   change the final form of the URL before string conversion.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   */
  public function generateAbsolute(string $uri): \Generator;

  /**
   * Generates relative web-accessible URL objects.
   *
   * @param string $uri
   *   The URI to a file for which we need an external URL, or the path to a
   *   shipped file.
   *
   * @return \Drupal\Core\Url
   *   A Url object with absolute URL. Use setAbsolute() on the Url object to
   *   change the final form of the URL before string conversion.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   */
  public function generateRelative(string $uri): \Generator;

  /**
   * Generates web-accessible URL strings, configured based on the value of the
   * $absolute paramter.
   *
   * @param string $uri
   *   The URI to a file for which we need an external URL, or the path to a
   *   shipped file.
   *
   * @return string
   *   For a local URL (matching domain), a string containing the URL that may
   *   be used to access the file.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   *
   * @see \Drupal\purge_queuer_file_urls\Service\ConfigurationAwareFileUrlGenerator::$absolute.
   */
  public function generateString(string $uri): \Generator;

  /**
   * Genreates absolute web-accessible URL strings.
   *
   * @param string $uri
   *   The URI to a file for which we need an external URL, or the path to a
   *   shipped file.
   *
   * @return string
   *   For a local URL (matching domain), a string containing the URL that may
   *   be used to access the file.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   */
  public function generateAbsoluteString(string $uri): \Generator;

  /**
   * Generates relative web-accessible URL strings.
   *
   * @param string $uri
   *   The URI to a file for which we need an external URL, or the path to a
   *   shipped file.
   *
   * @return string
   *   For a local URL (matching domain), a string containing the URL that may
   *   be used to access the file.
   *
   * @throws \Drupal\Core\File\Exception\InvalidStreamWrapperException
   *   If a stream wrapper could not be found to generate an external URL.
   */
  public function generateRelativeString(string $uri): \Generator;

}
