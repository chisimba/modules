<?PHP

require_once('wsdlparser_class_inc.php');

$wp = new wsdlparser('http://kngforge.uwc.ac.za/kng_unstable/modules/webservices/server.php?wsdl');
print_r($wp->getDocs());