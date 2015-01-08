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

interface Services_XIO_HttpProxy
{
  function createData($key, $body = "", array $opts = array());
  function retrieveData($key, array $params = array(), array $opts = array());
  function updateData($key, $body = "", array $opts = array());
  function deleteData($key, array $opts = array());
}
