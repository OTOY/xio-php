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

class Services_XIO_Exception extends ErrorException
{
    protected $context;
    protected $errors;

    public function __construct($message, $errors = null, $code = null, $severity = E_ERROR, $filename = null,
                         $lineno = null, array $context = array()) {
        parent::__construct($message, $code, $severity, $filename, $lineno);
        $this->errors = ($decode = json_decode($errors)) ? new Services_XIO_Error($decode) : $errors;
        $this->context = $context;
    }

    /**
     * Return array that points to the active symbol table at the point the error
     * occurred. In other words, it will contain an array of every variable that
     * existed in the scope the error was triggered in.
     *
     * @return array
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Return string containing any errors returned from the code that threw the
     * exception.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
