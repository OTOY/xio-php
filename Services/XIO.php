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
 * @access   private
 */

function Services_XIO_autoload($className)
{
    if (substr($className, 0, 12) != 'Services_XIO') {return false;}
    $file = str_replace('_', '/', $className);
    $file = str_replace('Services/', '', $file);
    return include dirname(__FILE__) . "/$file.php";
}
spl_autoload_register('Services_XIO_autoload');

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

class Services_XIO extends Services_XIO_Base
{
    const USER_AGENT = 'XIO-PHP v1.0.0';

    /**
    * Contains the HTTP communication class
    * 
    * @var Services_XIO_Http
    */
    protected $http;
    /**
    * Contains the default API version
    * 
    * @var string
    */
    protected $version;

    /**
    * Provides access the X.IO Streams API
    * 
    * Valid functions: create, details, cancel
    *
    * @var Services_XIO_Streams
    */
    public $streams;

    /**
    * Initialize the Services_XIO class and sub-classes.
    *
    * @param string            $api_key_id      API Key ID
    * @param string            $api_secret_key  API Secret Key
    * @param string            $api_version     API version
    * @param string            $api_host        API host
    * @param bool              $debug           Enable debug mode
    */
    public function __construct(
        $api_key_id = NULL,
        $api_secret_key = NULL,
        $api_version = 'v1',
        $api_host = 'https://api.x.io',
        $debug = false
    )
    {
        // Check that library dependencies are met
        if (strnatcmp(phpversion(),'5.2.0') < 0) {
            throw new Services_XIO_Exception('PHP version 5.2 or higher is required.');
        }
        if (!function_exists('json_encode')) {
            throw new Services_XIO_Exception('JSON support must be enabled.');
        }
        if (!function_exists('curl_init')) {
            throw new Services_XIO_Exception('cURL extension must be enabled.');
        }

        $this->version = $api_version;
        $this->http = new Services_XIO_Http(
            $api_host,
            array("curlopts" => array(
              CURLOPT_USERAGENT => self::USER_AGENT,
              CURLOPT_CAINFO => dirname(__FILE__) . "/XIO/ca-bundle.crt",
            ), "api_key_id" => $api_key_id, "api_secret_key" => $api_secret_key, "debug" => $debug)
        );

        $this->streams = new Services_XIO_Streams($this);
    }

    /**
    * GET the resource at the specified path.
    *
    * @param string $path   Path to the resource
    * @param array  $params Query string parameters
    * @param array  $opts   Optional overrides
    *
    * @return object The object representation of the resource
    */
    public function retrieveData($path, array $params = array(), array $opts = array())
    {
        return empty($params)
            ? $this->_processResponse($this->http->get($this->_getApiPath($opts) . $path))
            : $this->_processResponse(
                $this->http->get($this->_getApiPath($opts) . $path . "?" . http_build_query($params, '', '&'))
            );
    }

    /**
    * DELETE the resource at the specified path.
    *
    * @param string $path   Path to the resource
    * @param array  $opts   Optional overrides
    *
    * @return object The object representation of the resource
    */
    public function deleteData($path, array $opts = array())
    {
        return $this->_processResponse($this->http->delete($this->_getApiPath($opts) . $path));
    }

    /**
    * POST to the resource at the specified path.
    *
    * @param string $path   Path to the resource
    * @param string $body   Raw body to post
    * @param array  $opts   Optional overrides
    *
    * @return object The object representation of the resource
    */
    public function createData($path, $body = "", array $opts = array())
    {
        $headers = array('Content-Type' => 'application/json');
        return empty($body)
            ? $this->_processResponse($this->http->post($this->_getApiPath($opts) . $path, $headers))
            : $this->_processResponse(
                $this->http->post(
                    $this->_getApiPath($opts) . $path,
                    $headers,
                    $body
                )
            );
    }

    /**
    * PUT to the resource at the specified path.
    *
    * @param string $path   Path to the resource
    * @param string $body   Raw body to post
    * @param array  $opts   Optional overrides
    *
    * @return object The object representation of the resource
    */
    public function updateData($path, $body = "", array $opts = array())
    {
        $headers = array('Content-Type' => 'application/json');
        return empty($params)
            ? $this->_processResponse($this->http->put($this->_getApiPath($opts) . $path, $headers))
            : $this->_processResponse(
                $this->http->put(
                    $this->_getApiPath($opts) . $path,
                    $headers,
                    $body
                )
            );
    }

    private function _getApiPath($opts = array())
    {
        return isset($opts['no_transform'])
            ? ""
            : "/" . (
                isset($opts['api_version'])
                ? $opts['api_version']
                : $this->version
            ) . "/";
    }

    private function _processResponse($response)
    {
        list($status, $headers, $body) = $response;
        if ( $status == 204 || (($status == 200 || $status == 201) && trim($body) == "")) {
            return TRUE;
        }
        if (empty($headers['Content-Type'])) {
            throw new Services_XIO_Exception('Response header is missing Content-Type', $body);
        }
        switch ($headers['Content-Type']) {
            case 'application/json':
            case 'application/json; charset=utf-8':
                return $this->_processJsonResponse($status, $headers, $body);
                break;
        }
        throw new Services_XIO_Exception(
            'Unexpected content type: ' . $headers['Content-Type'], $body);
    }

    private function _processJsonResponse($status, $headers, $body)
    {
        $decoded = json_decode($body);
        if ($status >= 200 && $status < 300) {
            return $decoded;
        }
        throw new Services_XIO_Exception(
            "Invalid HTTP status code: " . $status, $body
        );
    }
}
