<?PHP

require_once('wsdlparser_class_inc.php');

$wp = new wsdlparser("http://kngforge.uwc.ac.za/kng_unstable/modules/mirrordb/mirrordb.php?wsdl");
$docs = $wp->getDocs();
//var_dump($docs);
$ns = $wp->getTargetNameSpace();
//var_dump($ns);
$service = $wp->declareService();
//var_dump($service);
$wp->getOperations();
//var_dump($wp->service);
echo $wp->writeCode();
