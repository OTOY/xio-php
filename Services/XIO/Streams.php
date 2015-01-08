<?php
/**
 * X.IO API client interface.
 *
 * @category Services
 * @package  Services_XIO
 * @author   Mike Christopher <m@x.io>
 * @version  Release: 2.1.2
 * @license  http://creativecommons.org/licenses/MIT/MIT
 * @link     http://github.com/otoyinc/xio-php
 */

class Services_XIO_Streams extends Services_XIO_Base
{
  /**
   * Create a X.IO stream
   *
   * @param array  $stream  Array of attributes to use when creating the stream
   * @param array  $params  Optional overrides
   *
   * @return Services_XIO_Stream The object representation of the resource
   */
  public function create($stream = NULL, $params = array()) {
    if(is_string($stream)) {
      $json = trim($stream);
    } else if(is_array($stream)) {
      $json = json_encode($stream);
    } else {
      throw new Services_XIO_Exception(
        'Stream parameters required to create stream.');
    }
    return new Services_XIO_Stream($this->proxy->createData("streams", $json, $params));
  }

  /**
   * Return details of a specific stream
   *
   * @param string $stream_id  ID of the stream you want details for
   * @param array  $params     Optional overrides
   *
   * @return Services_XIO_Stream The object representation of the resource
   */
  public function details($stream_id, $params = array()) {
    return new Services_XIO_Stream($this->proxy->retrieveData("streams/$stream_id", array(), $params));
  }

  /**
   * Cancel and disconnect stream
   *
   * @param string   $stream_id  ID of the stream you want to cancel
   * @param array    $params     Optional overrides
   *
   * @return bool If the operation was successful
   */
  public function cancel($stream_id, $params = array()) {
    return $this->proxy->deleteData("streams/$stream_id", $params);
  }
}
