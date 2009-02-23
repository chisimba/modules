<?php

class zoopylib
{
    protected $items;

    public function init()
    {
        $this->items = array();
    }

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

    public function show()
    {
        $dom = new DOMDocument();
        $dom->loadHTML('<ul></ul>');
        $ul = $dom->getElementsByTagName('ul')->item(0);
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

        return $dom->saveHTML();
    }
}
