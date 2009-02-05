<?php

    class computerscience extends controller
    {
        public $objLanguage;
        
        public function init()
        {
            //Retrieve the action parameter from the querystring
            #$this->action = $this->getParam('action', Null);
            //Instantiate the language object
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objDict = & $this->getObject('editform');
            $this->objXml = $this->getObject('xmlthing', 'utilities');
        }
        
        # return "editadd_tpl.php";        
        public function dispatch($action)
        {
            switch ($action) {
                //Default to view and display view template
                case "add":
                    $this->objXml->createDoc();
                    $pattern = $this->getParam('txtPatternOne');
                    $that = "";
                    $template = "";
                    $document = "";
                    #Working with Category One
                    if($pattern != "")
                    {
                        $that = $this->getParam('txtThatOne');
                        $template = $this->getParam('txtTemplateOne');
                        $this->objXml->startElement("category");
                        $this->objXml->writeElement("pattern", strtoupper($pattern));
                        if($that != "")
                        {  
                           $this->objXml->writeElement("that", $that);
                        }
                        $this->objXml->writeElement("template", $template);
                        $this->objXml->endElement();
                    }
                    
                    $pattern = "";
                    $that = "";
                    $template = "";
                    
                    #Working with Category Two
                    $pattern = $this->getParam('txtPatternTwo');
                    if($pattern != "")
                    {
                        $that = $this->getParam('txtThatTwo');
                        $template = $this->getParam('txtTemplateTwo');
                        $this->objXml->startElement("category");
                        $this->objXml->writeElement("pattern", strtoupper($pattern));
                        if($that != "")
                        {  
                            $this->objXml->writeElement("that", $that);
                        }
                        $this->objXml->writeElement("template", $template);
                        $this->objXml->endElement();
                    }
                    
                    $pattern = "";
                    $that = "";
                    $template = "";
                    
                    #Working with Category Three
                    $pattern = $this->getParam('txtPatternThree');
                    if($pattern != "")
                    {
                        $that = $this->getParam('txtThatThree');
                        $template = $this->getParam('txtTemplateThree');
                        $this->objXml->startElement("category");
                        $this->objXml->writeElement("pattern", strtoupper($pattern));
                        if($that != "")
                        {  
                            $this->objXml->writeElement("that", $that);
                        }
                        $this->objXml->writeElement("template", $template);
                        $this->objXml->endElement();
                    }
                    
                    $pattern = "";
                    $that = "";
                    $template = "";
                    
                    #Working with Category Four
                    $pattern = $this->getParam('txtPatternFour');
                    if($pattern != "")
                    {
                        $that = $this->getParam('txtThatFour');
                        $template = $this->getParam('txtTemplateFour');
                        $this->objXml->startElement("category");
                        $this->objXml->writeElement("pattern", strtoupper($pattern));
                        if($that != "")
                        {  
                            $this->objXml->writeElement("that", $that);
                        }
                        $this->objXml->writeElement("template", $template);
                        $this->objXml->endElement();
                    }                    
                    
                    $pattern = "";
                    $that = "";
                    $template = "";
                    
                    #Working with Category Five
                    $pattern = $this->getParam('txtPatternFive');
                    if($pattern != "")
                    {
                        $that = $this->getParam('txtThatFive');
                        $template = $this->getParam('txtTemplateFive');
                        $this->objXml->startElement("category");
                        $this->objXml->writeElement("pattern", strtoupper($pattern));
                        if($that != "")
                        {  
                            $this->objXml->writeElement("that", $that);
                        }
                        $this->objXml->writeElement("template", $template);
                        $this->objXml->endElement();
                    }
                                
                    $pattern = "";
                    $that = "";
                    $template = "";
                    
                    #Working with Category Six
                    $pattern = $this->getParam('txtPatternSix');
                    if($pattern != "")
                    {
                        $that = $this->getParam('txtThatSix');
                        $template = $this->getParam('txtTemplateSix');
                        $this->objXml->startElement("category");
                        $this->objXml->writeElement("pattern", strtoupper($pattern));
                        if($that != "")
                        {  
                            $this->objXml->writeElement("that", $that);
                        }
                        $this->objXml->writeElement("template", $template);
                        $this->objXml->endElement();
                    }
                    
                    $document = $this->objXml->dumpXML();
                    // unhtmlentities $document
                    $table = array_flip(get_html_translation_table(HTML_ENTITIES));
                    $document = strtr($document, $table);
                    $filename = '/home/developer/podder/std-scienceforfun.aiml';
                    // write to file
                    file_put_contents($filename, $document) or die('Could not write to file');                  
                    $this->setVarByRef('str', $document);
                    return 'editadd_tpl.php';
                    break;
                    default:
                    $str = $this->objDict->show();
                    $this->setVar('str', $str);
                    return 'editadd_tpl.php';
                    break;
            }
        }       
    }    
?>