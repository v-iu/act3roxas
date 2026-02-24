<?php
$client = new SoapClient(null, [
    'location' => 'http://localhost:8000/soap.php',
    'uri'      => 'http://localhost/'
]);
echo $client->hello('Bob');          // should print "Hello, Bob!"
try {
    var_dump($client->getUser(1));   // returns user object or throws
} catch (SoapFault $f) {
    echo "SOAP fault: {$f->getMessage()}";
}