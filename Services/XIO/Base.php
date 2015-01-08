<?php
/**
 * X.IO API client interface.
 *
 * @category Services
 * @package  Services_XIO
 * @author   Mike Christopher <m@x.io>
 * @version  Release: 1.0.0
 * @license  http://creativecommons.org/licenses/MIT
 * @link     http://github.com/otoyinc/xio-php
 */

abstract class Services_XIO_Base
    implements Services_XIO_HttpProxy
{

  protected $proxy;

  public function __construct(Services_XIO_HttpProxy $proxy)
  {
    $this->proxy = $proxy;
  }

  public function createData($path, $body = "", array $opts = array())
  {
      return $this->proxy->createData($path, $body, $opts);
  }

  public function retrieveData($path, array $params = array(), array $opts = array())
  {
      return $this->proxy->retrieveData($path, $params, $opts);
  }

  public function updateData($path, $body = "", array $opts = array())
  {
      return $this->proxy->updateData($path, $body, $opts);
  }

  public function deleteData($path, array $opts = array())
  {
      return $this->proxy->deleteData($path, $opts);
  }
}
