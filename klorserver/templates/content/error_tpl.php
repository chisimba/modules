

<?php
/*** SOAP TEST CLIENT ***/

// Pull in the NuSOAP code
require_once('lib/nusoap.php');
// Create the client instance
$client = new soapclient('http://localhost/nextgen/modules/klorserver/server.php?wsdl', true);
// Check for an error

echo $this->getParam('hello');
 
$err = $client->getError();
if ($err) {
    // Display the error
    echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    // At this point, you know the call that follows will fail
}
// Call the SOAP method
$result = $client->call('hello', array('name' => 'Paul'));
// Check for a fault
if ($client->fault) {
    echo '<h2>Fault</h2><pre>';
    print_r($result);
    echo '</pre>';
} else {
    // Check for errors
    $err = $client->getError();
    if ($err) {
        // Display the error
        echo '<h2>Error</h2><pre>' . $err . '</pre>';
    } else {
        // Display the result
        echo '<h2>Result</h2><pre>';
        print_r($result);
    echo '</pre>';
    }
}
// Display the request and response
echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
// Display the debug messages
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';


/*
$objTable = &$this->getObject('htmltable','htmlelements');
$objHeading = &$this->getObject('htmlheading','htmlelements');

$errormsg = $objLanguage->languageText('mod_klorserver_errormsg');
echo $errormsg ;
$pgTitle = $objHeading;
$pgTitle->type = 1;

$heading = $objTable; 
$heading->startRow();
$heading->addCell($pgTitle->show());
$heading->endRow();
*/
?>