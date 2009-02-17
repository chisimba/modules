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
            $this->loadClass('checkbox', 'htmlelements');
            $this->loadClass('label', 'htmlelements');

            $checkbox = new checkbox('acceptedterms');
            $checkbox->setId('acceptedterms');
            $this->content .= $checkbox->show();

            $labelText = $this->objLanguage->languageText('mod_blog_terms_accept', 'blog');
            $label = new label($labelText, 'acceptedterms');
            $this->content .= $label->show();

            $acceptedJs = 'jQuery.getJSON("index.php?module=blog&action=blogadmin&mode=acceptterms")';
            $declinedJs = 'document.location="index.php?module=blog&action=viewblog"';
            $closeJs = 'document.getElementById("acceptedterms").checked?'.$acceptedJs.':'.$declinedJs;

            $this->setClose($closeJs);
        }
        return parent::show();
    }
}
