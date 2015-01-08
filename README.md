X.IO API PHP Library
==========================

Author:  [Mike Christopher] (mike.christopher (a) otoy (.) c&#1;om)

Company: [OTOY](http://www.otoy.com)

Version: 1.0.0

Date:    2015-01-08

Repository: <http://github.com/otoyinc/xio-php/>

Parts of this library are based on <http://github.com/twilio/twilio-php> and <http://github.com/zencoder/zencoder-php>

For more details on the X.IO API requirements visit
<http://docs.x.io/v1/docs>

To start working with the library, create a new instance of the Services_XIO class, passing
your API Key ID as the first parameter and API Secret Key as the second.

```php
$xio = new Services_XIO('R45KU9BJWTKM4ATGZNKF29LJZ', 'F2kiGEZuhv7D7AEujAiwgLKVjZjP28pa0P96pbiw');
```

Once you have created the object, you can use it to interact with the API. For full information,
see the Documentation folder, but here is a quick overview of some of the functions that can be
called:

```php
$xio->streams->create($array);
$xio->streams->details($stream_id);
$xio->streams->cancel($stream_id);
```

Any errors will throw a Services_XIO_Exception. You can call getErrors() on an exception
and it will return any errors received from the X.IO API.


STARTING A STREAM
-----------------

The Services_XIO_Streams object creates an stream session using [cURL](http://php.net/manual/en/book.curl.php)
to send [JSON](http://en.wikipedia.org/wiki/JSON) formatted parameters to X.IO's API.

### Step 1

Visit the [API documentation](http://docs.x.io/v1/docs) and build a sample request.

### Step 2

Copy the successful JSON string, starting with the first curly brace "{",
and pass it as the parameters for a new Services_XIO_Streams object. Execute the script on your
server to test that it works.

#### Example

```php
<?php

// Make sure this points to a copy of XIO.php on the same server as this script.
require_once('Services/XIO.php');

$stream = null;
try {
  // Initialize the Services_XIO class
  $xio = new Services_XIO('R45KU9BJWTKM4ATGZNKF29LJZ', 'F2kiGEZuhv7D7AEujAiwgLKVjZjP28pa0P96pbiw');

  // New Encoding Job
  $stream = $xio->streams->create(
    array(
      "application_id" => "7cd9272c77ef44aeb460a70de434067a",
      "version_id" => "980a84ff564b4c459cc5a658fd3fa838"
    )
  );

  // Success if we got here
  echo "w00t! \n\n";
  echo "Stream ID: ".$stream->id."\n";
  echo "Application name: ".$stream->application->name."\n";
} catch (Services_XIO_Exception $e) {
  // If were here, an error occured
  echo "Fail :(\n\n";
  echo "Errors:\n";
  echo $e->getErrors()->error."\n\n";
  echo "Full exception dump:\n\n";
  print_r($e);
}

echo "\nAll Stream Attributes:\n";
var_dump($stream);

?>
```

VERSIONS
---------

    Version 1.0.0   - 2015-01-08    Initial release, supports streams.
