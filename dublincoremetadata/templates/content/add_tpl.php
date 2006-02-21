<?php
    $form = & $this->newObject('form','htmlelements');
    $textarea = & $this->newObject('textarea','htmlelements');
    $objButton = & $this->newObject('button','htmlelements');
    //$form = & $this->newObject('form','htmlelements');
    
    //setup form
    $form->name='frm_dublincore';
    $form->setAction($this->uri(array('action'=>'save')));
    $form->setDisplayType(2);
    
    $textarea->setColumns(50);
    $textarea->setRows(2);
    $textarea->setContent(NULL);
    
    $objButton=new button('save');
    $objButton->setToSubmit();
    $objButton->setValue($this->objLanguage->languageText("mod_contextadmin_save"));
    
    //title
    $title = $textarea;    
    $title->name = 'title';
    $title->label = $this->objLanguage->languageText("word_title");
    
    //subject
    $subject = $textarea;
    $subject->name = 'subject';
    $subject->label = $this->objLanguage->languageText("mod_dublin_subject");
    
    //description
    $description = $textarea;
    $description->name = 'description';
    $description->label = $this->objLanguage->languageText("mod_dublin_description");
    
    //source
    $source = $textarea;
    $source->name = 'source';
    $source->label = $this->objLanguage->languageText("mod_dublin_source");
    
    //type
    $type = $textarea;
    $type->name = 'type';
    $type->label = $this->objLanguage->languageText("mod_dublin_subject");
    
    //relationship
    $relationship = $textarea;
    $relationship->name = 'relationship';
    $relationship->label = $this->objLanguage->languageText("mod_dublin_relationship");
    
    //coverage
    $coverage = $textarea;
    $coverage->name = 'relationship';
    $coverage->label = $this->objLanguage->languageText("mod_dublin_coverage");
    
    //creator
    $creator = $textarea;
    $creator->name = 'creator';
    $creator->label = $this->objLanguage->languageText("mod_dublin_creator");
    
    //publisher
    $publisher = $textarea;
    $publisher->name = 'publisher';
    $publisher->label = $this->objLanguage->languageText("mod_dublin_publisher");
    
    //contributor
    $contributor = $textarea;
    $contributor->name = 'contributor';
    $contributor->label = $this->objLanguage->languageText("mod_dublin_contributor");
    
    //rights
    $rights = $textarea;
    $rights->name = 'rights';
    $rights->label = $this->objLanguage->languageText("mod_dublin_rights");

    //relationship
    $date = $textarea;
    $date->name = 'date';
    $date->label = $this->objLanguage->languageText("mod_dublin_date");
    
    //format
    $format = $textarea;
    $format->name = 'format';
    $format->label = $this->objLanguage->languageText("mod_dublin_format");
    
    //relationship
    $relationship = $textarea;
    $relationship->name = 'relationship';
    $relationship->label = $this->objLanguage->languageText("mod_dublin_relationship");
    
    //identifier
    $identifier = $textarea;
    $identifier->name = 'identifier';
    $identifier->label = $this->objLanguage->languageText("mod_dublin_identifier");
    
    //language
    $language = $textarea;
    $language->name = 'relationship';
    $language->label = $this->objLanguage->languageText("mod_dublin_language");
    
    //audience
    $audience = $textarea;
    $audience->name = 'audience';
    $audience->label = $this->objLanguage->languageText("mod_dublin_audience");
    
    $form->addToForm($title);
    $form->addToForm($subject);
    $form->addToForm($description);
    $form->addToForm($source);
    $form->addToForm($type);
    $form->addToForm($relationship);
    $form->addToForm($coverage);
    $form->addToForm($creator);
    $form->addToForm($publisher);
    $form->addToForm($contributor);
    $form->addToForm($rights);
    $form->addToForm($date);
    $form->addToForm($format);
    $form->addToForm($identifier);
    $form->addToForm($language);
    $form->addToForm($audience);
    $form->addToForm($objButton);
    
    echo '<h1>'. $this->objLanguage->languageText("mod_dublin_dcm"). '</h1>';
    echo $form->show();
    
?>