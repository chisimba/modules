<?php

class zoopylib
{
    /**
     * The feed items to be displayed.
     *
     * @access protected
     * @var array
     */
    protected $items;

    /**
     * Initialises the instance variables.
     */
    public function init()
    {
        $this->items = array();
    }

    /**
     * Loads and parses the RSS feed from the URI specified.
     *
     * @access public
     * @param string $uri The URI to load the feed from.
     */
    public function loadFeed($uri)
    {
        $dom = new DOMDocument();
        $dom->load($uri);
        $domItems = $dom->getElementsByTagName('item');
        for ($i = 0; $i < $domItems->length; $i++) {
            $domItem = $domItems->item($i);
            $item = array();
            $item['title'] = $domItem->getElementsByTagName('title')->item(0)->textContent;
            $item['link'] = $domItem->getElementsByTagName('link')->item(0)->textContent;
            $item['image'] = $domItem->getElementsByTagName('content')->item(0)->getAttribute('url');
            preg_match_all('#/([0-9]+)/thumb#i', $item['image'], $matches);
            $item['image'] = 'http://www.zoopy.com/data/media/' . $matches[1][0] . '/thumb-150x150f.jpg';
            $this->items[] = $item;
        }
    }

    /**
     * Generates the HTML output to send back to the user agent.
     *
     * @access public
     * @return string The HTML output.
     */
    public function show()
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $ul = $dom->createElement('ul');
        $ul->setAttribute('class', 'zoopy');
        foreach ($this->items as $item) {
            $img = $dom->createElement('img');
            $img->setAttribute('src', $item['image']);
            $img->setAttribute('alt', $item['title']);
            $img->setAttribute('title', $item['title']);
            $a = $dom->createElement('a');
            $a->setAttribute('href', $item['link']);
            $a->appendChild($img);       
            $li = $dom->createElement('li');
            $li->appendChild($a);
            $ul->appendChild($li);
        }

        return $dom->saveXML($ul);
    }
}
