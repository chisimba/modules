<?php

class skypestatus extends object
{
    public function getStatus($username)
    {
        $uri = sprintf('http://mystatus.skype.com/%s.xml', rawurlencode($username));
        $document = new DOMDocument();
        $document->load($uri);
        $elements = $document->getElementsByTagName('presence');
        $status = array();
        foreach ($elements as $element) {
            $lang = $element->getAttribute('xml:lang');
            $status[$lang] = $element->textContent;
        }
        return $status;
    }
}
