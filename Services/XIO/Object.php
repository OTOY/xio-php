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

class Services_XIO_Object
{
    public function __construct($params)
    {
        $this->_update_attributes($params);
    }

    private function _update_attributes($attributes = array())
    {
        if (isset($attributes)) {
            foreach($attributes as $attr_name => $attr_value) {
                if(empty($this->$attr_name)) $this->$attr_name = $attr_value;
            }
        }
    }
}
