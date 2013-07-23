<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of block_frontend_home
 *
 * @author monwabisi
 */
class block_frontend_home extends object {
        //put your code here
        var $objDom;
        var $objLanguage;

        function init() {
                $this->title = "This is the developer frontend";
                $this->objLanguage = $this->getObject("language", "language");
        }

        function buildform() {
                $this->objDom = new DOMDocument('utf-8');
                /**
                 * @var wrapperDiv wrapper to contain all the block content
                 * ==wrapper div==
                 */
                $wrapperDiv = $this->objDom->createElement('div');
                /**
                 * @var $header element to contain content displayed at the top of the page
                 * ==Header div===
                 */
                $header = $this->objDom->createElement('header');
                $header->setAttribute('id', 'header');
                //header content
                $h1 = $this->objDom->createElement('h1');
                $h1->appendChild($this->objDom->createTextNode('The payment Pebble'));
                $h3 = $this->objDom->createElement('h3');
                $h3->appendChild($this->objDom->createTextNode('Accept card payments in a mobile app'));
                $header->appendChild($h1);
                $header->appendChild($h3);
                /**
                 * @var $article article below the header
                 */
                $sectionOne = $this->objDom->createElement('section');
                $article = $this->objDom->createElement('article');
                $articleParagraph = $this->objDom->createElement('p');
                $articleParagraph->appendChild($this->objDom->createTextNode($this->objLanguage->languageText('mod_frontend_paragraphone', 'frontend')));
                $articleParagraph->appendChild($this->objDom->createElement('br'));
                $articleParagraph->appendChild($this->objDom->createElement('br'));
                $articleParagraph->appendChild($this->objDom->createTextNode($this->objLanguage->languageText('mod_frontend_paragraphtwo', 'frontend')));
                $article->appendChild($articleParagraph);
                $sectionOne->appendChild($article);
                $sectionTwo = $this->objDom->createElement('section');
                $h1 = $this->objDom->createElement('h1');
                $h1->appendChild($this->objDom->createTextNode('How it works'));
                $sectionTwo->appendChild($h1);
                //put the header inside the wrapper
                $wrapperDiv->appendChild($header);
                $wrapperDiv->appendChild($sectionOne);
                $wrapperDiv->appendChild($sectionTwo);
                //put the wrapper into the document
                $this->objDom->appendChild($wrapperDiv);

                return $this->objDom->saveHTML();
        }

        function show() {
                return $this->buildform();
        }

}

?>
