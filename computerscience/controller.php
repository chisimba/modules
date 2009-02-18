<?php

    class computerscience extends controller
    {
        public $objLanguage;

        public function init()
        {
            // Instantiate the language object
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objDict = & $this->getObject('editform');
            $this->objXml = $this->getObject('xmlthing', 'utilities');
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->objUser = $this->getObject('user', 'security');
        }

        public function dispatch($action)
        {
            switch ($action) {
                //Default to view and display view template
                case "add":
                    if(!file_exists($this->objConfig->getContentBasepath().'users/'.$this->objUser->userId().'/aiml/')) {
                        mkdir ($this->objConfig->getContentBasepath().'users/'.$this->objUser->userId().'/aiml/', 0777);
                        chmod ($this->objConfig->getContentBasepath().'users/'.$this->objUser->userId().'/aiml/', 0777);
                    }
                    else {
                        chmod ($this->objConfig->getContentBasepath().'users/'.$this->objUser->userId().'/aiml/', 0777);
                    }
                    $filename = $this->objConfig->getContentBasepath().'users/'.$this->objUser->userId().'/aiml/std-cs4fn.aiml';
                    if(!file_exists($filename)) {
                        $this->objXml->createDoc();
                        $this->objXml->startElement('aiml');
                        $this->objXml->writeAtrribute('version', '1.0');
                    }
                    else {
                        $this->objXml->editDoc();
                    }
                    $pattern = $this->getParam('txtPatternOne', "");
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

                    // end the aiml element
                    $this->objXml->endElement();
                    $document = $this->objXml->dumpXML();
                    // unhtmlentities $document
                    $table = array_flip(get_html_translation_table(HTML_ENTITIES));
                    $document = strtr($document, $table);
                    if(!file_exists($filename)) {
                        // write to file as a new file
                        file_put_contents($filename, $document) or die('Could not write to file');
                    }
                    else {
                        // Open the file and read it, then append to it
                        $contents = file_get_contents($filename);
                        $document = $contents.$document;
                        file_put_contents($filename, $document);
                    }
                    $message = $this->objLanguage->languageText("mod_computerscience_word_updated", "computerscience");
                    $this->nextAction('', array('message' => $message));


                    return 'editadd_tpl.php';
                    break;

                    default:
                        $message = $this->getParam('message', NULL);

                        $str = $this->objDict->show();
                        $this->setVar('message', $message);
                        $this->setVar('str', $str);

                        return 'editadd_tpl.php';
                        break;
            }
        }
    }
?>