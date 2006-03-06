<?PHP
ini_set('soap.wsdl_cache_enabled', 0); // disable WSDL cache

class wsdlparser //extends object
{
    private $dom;
    private $client;
    private $e; //error messages
    public $wsdl;
    public $targetNamespace = '';
    private $doc;
    public $service;
    private $keywords;
    private $primitive_types;

    public function __construct($wsdl)
    {
        $this->wsdl = $wsdl;

        $options = array('proxy_host' => "cache.uwc.ac.za",
        'proxy_port' => 8080,
        'proxy_login' => "pscott",
        'proxy_password' => "scott");

        try
        {
            $this->client = new SoapClient($this->wsdl, $options);
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
        $this->doc = $doc;
        return $this->doc;

    }

    public function getTargetNameSpace()
    {
        // get targetNamespace
        $this->targetNamespace = '';
        $nodes = $this->dom->getElementsByTagName('definitions');
        foreach($nodes as $node) {
            $this->targetNamespace = $node->getAttribute('targetNamespace');
        }
        return $this->targetNamespace;

    }

    public function declareService()
    {
        // declare service
        $service = array('class' => $this->dom->getElementsByTagNameNS('*', 'service')->item(0)->getAttribute('name'),
        'wsdl' => $this->wsdl,
        'doc' => $this->doc['service'],
        'functions' => array()
        );

        // PHP keywords - can not be used as constants, class names or function names!
        $this->keywords = array('and', 'or', 'xor', 'as', 'break', 'case',
                                'cfunction', 'class',
                                'continue', 'declare', 'const', 'default', 'do', 'else',
                                'elseif', 'enddeclare', 'endfor', 'endforeach', 'endif',
                                'endswitch', 'endwhile', 'eval', 'extends', 'for', 'foreach',
                                'function', 'global', 'if', 'new', 'old_function', 'static',
                                'switch', 'use', 'var', 'while', 'array', 'die', 'echo',
                                'empty', 'exit', 'include', 'include_once', 'isset', 'list',
                                'print', 'require', 'require_once', 'return', 'unset',
                                '__file__', '__line__', '__function__', '__class__',
                                'abstract'
                                );

        // ensure legal class name (I don't think using . and whitespaces is allowed in terms of the SOAP standard, should check this out and may throw and exception instead...)
        $service['class'] = str_replace(' ', '_', $service['class']);
        $service['class'] = str_replace('.', '_', $service['class']);
        $service['class'] = str_replace('-', '_', $service['class']);

        if(in_array(strtolower($service['class']), $this->keywords)) {
            $service['class'] .= 'Service';
        }

        // verify that the name of the service is named as a defined class
        if(class_exists($service['class'])) {
            throw new Exception("Class '".$service['class']."' already exists");
        }
        $this->service = $service;
        return $this->service;

    }

    public function getOperations()
    {
        // get operations
        $operations = $this->client->__getFunctions();
        foreach($operations as $operation)
        {
            $matches = array();
            if(preg_match('/^(\w[\w\d_]*) (\w[\w\d_]*)\(([\w\$\d,_ ]*)\)$/', $operation, $matches)) {
                $returns = $matches[1];
                $call = $matches[2];
                $params = $matches[3];
            } else if(preg_match('/^(list\([\w\$\d,_ ]*\)) (\w[\w\d_]*)\(([\w\$\d,_ ]*)\)$/', $operation, $matches)) {
                $returns = $matches[1];
                $call = $matches[2];
                $params = $matches[3];
            } else { // invalid function call
                throw new Exception('Invalid function call: '.$function);
            }

            $params = explode(', ', $params);

            $paramsArr = array();
            foreach($params as $param) {
                $paramsArr[] = explode(' ', $param);
            }
            //  $call = explode(' ', $call);
            $function = array('name' => $call,
            'method' => $call,
            'return' => $returns,
            'doc' => $this->doc['operations'][$call],
            'params' => $paramsArr);

            // ensure legal function name
            if(in_array(strtolower($function['method']), $this->keywords)) {
                $function['name'] = '_'.$function['method'];
            }

            // ensure that the method we are adding has not the same name as the constructor
            if(strtolower($this->service['class']) == strtolower($function['method'])) {
                $function['name'] = '_'.$function['method'];
            }
            // ensure that there's no method that already exists with this name
            // this is most likely a Soap vs HttpGet vs HttpPost problem in WSDL
            // I assume for now that Soap is the one listed first and just skip the rest
            // this should be improved by actually verifying that it's a Soap operation that's in the WSDL file
            // QUICK FIX: just skip function if it already exists
            $add = true;
            foreach($this->service['functions'] as $func) {
                if($func['name'] == $function['name']) {
                    $add = false;
                }
            }
            if($add) {
                $this->service['functions'][] = $function;
            }
        }
        $types = $this->client->__getTypes();

        $this->primitive_types = array('string', 'int', 'long', 'float', 'boolean',
        'dateTime', 'double', 'short', 'UNKNOWN',
        'base64Binary'
        ); // TODO: dateTime is special, maybe use PEAR::Date or similar
        $this->service['types'] = array();
        foreach($types as $type) {
            $parts = explode("\n", $type);
            $class = explode(" ", $parts[0]);
            $class = $class[1];

            if( substr($class, -2, 2) == '[]' ) {
                // array skipping
                continue;
            }

            if( substr($class, 0, 7) == 'ArrayOf' ) {
                // skip 'ArrayOf*' types (from MS.NET, Axis etc.)
                continue;
            }

            $members = array();
            for($i=1; $i<count($parts)-1; $i++) {
                $parts[$i] = trim($parts[$i]);
                list($type, $member) = explode(" ", substr($parts[$i], 0, strlen($parts[$i])-1) );

                // check syntax
                if(preg_match('/^$\w[\w\d_]*$/', $member)) {
                    throw new Exception('illegal syntax for member variable: '.$member);
                    continue;
                }

                // IMPORTANT: Need to filter out namespace on member if presented
                if(strpos($member, ':')) {
                    // keep the last part
                    list($tmp, $member) = explode(':', $member);
                }

                // OBS: Skip member if already presented
                // (this shouldn't happen, but I've actually seen it in a WSDL-file)
                // "It's better to be safe than sorry" (ref Morten Harket)
                $add = true;
                foreach($members as $mem) {
                    if($mem['member'] == $member) {
                        $add = false;
                    }
                }
                if($add) {
                    $members[] = array('member' => $member, 'type' => $type);
                }
            }

            $this->service['types'][] = array('class' => $class, 'members' => $members);
        }
    }//end function

    public function writeCode()
    {
        // add types
        foreach($this->service['types'] as $type) {
            $code = "/**\n";
            $code .= " * ".$type['doc']."\n";
            $code .= " * \n";
            $code .= " * @package\n";
            $code .= " * @copyright\n";
            $code .= " */\n";
            $code .= "class ".$type['class']." {\n";
            foreach($type['members'] as $member) {
                $code .= "  /* ".$member['type']." */\n";
                $code .= "  public \$".$member['member'].";\n";
            }
            $code .= "}\n\n";

            //print "Writing ".$type['class'].".php...";
            $filename = $type['class'].".php";

        }

        // add service

        // page level docblock
        $code = "/**\n";
        $code .= " * ".$this->service['class']." class file\n";
        $code .= " * \n";
        $code .= " * @author    {author}\n";
        $code .= " * @copyright {copyright}\n";
        $code .= " * @package   {package}\n";
        $code .= " */\n\n";


        // require types
        foreach($this->service['types'] as $type) {
            $code .= "/**\n";
            $code .= " * ".$type['class']." class\n";
            $code .= " */\n";
            $code .= "require_once '".$type['class'].".php';\n";
        }

        $code .= "\n";

        // class level docblock
        $code .= "/**\n";
        $code .= " * ".$this->service['class']." class\n";
        $code .= " * \n";
        $code .= $this->parse_doc(" * ", $this->service['doc']);
        $code .= " * \n";
        $code .= " * @author    {author}\n";
        $code .= " * @copyright {copyright}\n";
        $code .= " * @package   {package}\n";
        $code .= " */\n";

        $code .= "class ".$this->service['class']." extends SoapClient {\n\n";
        $code .= "  public function ".$this->service['class']."(\$wsdl = \"".$this->service['wsdl']."\", \$options = array()) {\n";
        $code .= "    parent::__construct(\$wsdl, \$options);\n";
        $code .= "  }\n\n";

        foreach($this->service['functions'] as $function) {
            $code .= "  /**\n";
            $code .= $this->parse_doc("   * ", $function['doc']);
            $code .= "   *\n";

            $signature = array(); // used for function signature
            $para = array(); // just variable names
            foreach($function['params'] as $param) {
                $code .= "   * @param ".$param[0]." ".$param[1]."\n";
                $signature[] = (in_array($param[0], $this->primitive_types)) ? $param[1] : implode(' ', $param);
                $para[] = $param[1];
            }
            $code .= "   * @return ".$function['return']."\n";
            $code .= "   */\n";
            $code .= "  public function ".$function['name']."(".implode(', ', $signature).") {\n";

            $code .= "    return \$this->__call('".$function['method']."', array(";
            $params = array();
            if(!in_array('', $signature)) { // no arguments!
                foreach($signature as $param) {
                    if(strpos($param, ' ')) { // slice
                        $param = array_pop(explode(' ', $param));
                    }
                    $params[] = "      new SoapParam(".$param.", '".substr($param, 1, strlen($param))."')";
                }
                $code .= "\n      ";
                $code .= implode(",\n      ", $params);
                $code .= "\n      ),\n";
            } else {
                $code .= "),\n";
            }
            $code .= "      array(\n";
            $code .= "            'uri' => '".$this->targetNamespace."',\n";
            $code .= "            'soapaction' => ''\n";
            $code .= "           )\n";
            $code .= "      );\n";
            $code .= "  }\n\n";
        }
        $code .= "}\n\n";

        return $code;
    }

    public function parse_doc($prefix, $doc) {
        $code = "";
        $words = split(' ', $doc);
        $line = $prefix;
        foreach($words as $word) {
            $line .= $word.' ';
            if( strlen($line) > 90 ) { // new line
                $code .= $line."\n";
                $line = $prefix;
            }
        }
        $code .= $line."\n";
        return $code;
    }
}//end class
?>