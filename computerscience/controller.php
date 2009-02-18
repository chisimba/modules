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
                    $filename = $this->objConfig->getContentBasepath().'users/'.$this->objUser->userId().'/aiml/'.$this->objUser->userId().'_std-cs4fn.aiml';;

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
                            if(file_exists($filename)) {
                            $xml = simplexml_load_file($filename);
                            $cats = $xml->category;
                            foreach ($cats as $cat) {
                                $catarr[] = $cat->pattern;
                            }
                        }
                        else {
                            $catarr = array();
                        }
                            // so now we can get the correct pattern out
                           $this->objXml->writeElement("srai", $catarr[$that]);
                        }
                        else {
                            $this->objXml->writeElement("template", $template);
                        }
                        $this->objXml->endElement();
                    }

                    if(!file_exists($filename)) {
                        // write to file as a new file
                        // end the aiml element
                        $this->objXml->endElement();
                        $document = $this->objXml->dumpXML();

                        // unhtmlentities $document
                        $table = array_flip(get_html_translation_table(HTML_ENTITIES));
                        $document = strtr($document, $table);
                        file_put_contents($filename, $document);
                    }
                    else {
                        // Open the file and read it, then append to it
                        $contents = file_get_contents($filename);
                        $contents = str_replace("</aiml>", '', $contents);
                        // end the aiml element
                        $this->objXml->endElement();
                        $document = $this->objXml->dumpXML();
                        // unhtmlentities $document
                        $table = array_flip(get_html_translation_table(HTML_ENTITIES));
                        $document = strtr($document, $table);
                        $document .= "</aiml>";
                        $document = $contents.$document;

                        file_put_contents($filename, $document);
                    }
                    $message = $this->objLanguage->languageText("mod_computerscience_word_updated", "computerscience");
                    $this->nextAction('', array('message' => $message));


                    return 'editadd_tpl.php';
                    break;

                    case 'editaiml':
                        echo "not yet implemented";
                        die();
                        break;

                    case 'reloadbot':
                        echo "not yet implemented";
                        die();
                        break;

                    case 'publishaiml':
                        echo "publish to a specified dir so the bot can pick it up";
                        die();
                        break;

                    default:
                        $message = $this->getParam('message', NULL);
                        $filename = $this->objConfig->getContentBasepath().'users/'.$this->objUser->userId().'/aiml/'.$this->objUser->userId().'_std-cs4fn.aiml';
                        if(file_exists($filename)) {
                            $xml = simplexml_load_file($filename);
                            $cats = $xml->category;
                            foreach ($cats as $cat) {
                                $catarr[] = $cat->pattern;
                            }
                        }
                        else {
                            $catarr = array();
                        }

                        $str = $this->objDict->buildForm($catarr);
                        $this->setVar('message', $message);
                        $this->setVar('str', $str);


                        return 'editadd_tpl.php';
                        break;
            }
        }
    }
?>