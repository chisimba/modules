<?php

$GLOBALS['_globalObjEngine']->loadClass('jqdialogue', 'htmlelements');

class blogtermsdialogue extends jqdialogue
{
    protected $objLanguage;

    public function init()
    {
        parent::init();
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = $this->objLanguage->languageText('mod_blog_terms_title', 'blog');
    }

    public function show()
    {
        if ($this->content) {
            $this->content .= 'Test';
        }
        return parent::show();
    }
}
