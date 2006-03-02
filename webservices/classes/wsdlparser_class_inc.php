<?PHP
ini_set('soap.wsdl_cache_enabled', 0); // disable WSDL cache

class wsdlparser //extends object
{
    private $dom;
    private $client;
    private $e; //error messages
    public $wsdl;
    public $targetNamespace = '';

    public function __construct($wsdl)
    {
        $this->wsdl = $wsdl;

        try
        {
            $this->client = new SoapClient($this->wsdl);
        }
        catch(SoapFault $e)
        {
            die($e);
        }

        $this->dom = DOMDocument::load($this->wsdl);

    }

    public function getDocs()
    {
        // get documentation
        $nodes = $this->dom->getElementsByTagName('documentation');
        $doc = array('service' => '',
	           'operations' => array());
        foreach($nodes as $node) {
            if( $node->parentNode->localName == 'service' ) {
                $doc['service'] = trim($node->parentNode->nodeValue);
            } else if( $node->parentNode->localName == 'operation' ) {
                $operation = $node->parentNode->getAttribute('name');
                //$parameterOrder = $node->parentNode->getAttribute('parameterOrder');
                $doc['operations'][$operation] = trim($node->nodeValue);
            }
        }
        return $doc;

    }

    public function getTargetNameSpace()
    {
        // get targetNamespace
        $this->targetNamespace = '';
        $nodes = $this->dom->getElementsByTagName('definitions');
        foreach($nodes as $node) {
            $this->targetNamespace = $node->getAttribute('targetNamespace');
        }

    }




}//end class