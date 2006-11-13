<?
$this->loadClass('form','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('button','htmlelements');
$objButton=new button('submit','Go');
$objButton->setToSubmit();
$this->loadClass('textinput','htmlelements');

print "<h3>".$this->objLanguage->languageText('mod_userimport_remotetitle','userimport')."</h3>\n";

if (isset($faculties)){
    if (is_array($faculties)){
        $objFacultyDrop=new dropdown('faculty');
        $objForm1=new form('remoteimport',$this->uri(array('action'=>'remoteimport')));
        foreach ($faculties as $line)
        {
            $objFacultyDrop->addOption($line[0],htmlentities($line[1]));
        }
        $objFacultyDrop->setSelected($this->getParam('faculty',NULL));
        $objForm1->addToForm($objFacultyDrop->show());
        $objForm1->addToForm($objButton->show());
        if (isset($remoteFac)){
            $objForm1->addToForm("&nbsp;<br /><b>".$this->objLanguage->LanguageText('mod_userimport_facselec','userimport')."&nbsp;".$remoteFac."</b>");
        }
        print $this->objLanguage->LanguageText('mod_userimport_selectfaculty','userimport');
        print $objForm1->show()."\n"; 
    } else {
        print "<br /><br />\n";
        print "<span class='noRecordsMessage'>".$this->objLanguage->LanguageText('mod_userimport_nodata2','userimport')."</span>\n";
    }
}

if (isset($programs)){
    if (is_array($programs)){
        $objProgramDrop=new dropdown('program');
        $objForm2=new form('remoteimport',$this->uri(array('action'=>'remoteimport')));
        foreach ($programs as $line)
        {
            $objProgramDrop->addOption($line[0],htmlentities($line[1]));
        }
        $objProgramDrop->setSelected($this->getParam('program',NULL));
        $objForm2->addToForm($objProgramDrop->show());
        $objForm2->addToForm($objButton->show());
        $hidden= new textinput('faculty',$this->getParam('faculty',NULL),'hidden');
        $objForm2->addToForm($hidden->show());
        if (isset($remoteProg)){
            $objForm2->addToForm("&nbsp;<br /><b>".$this->objLanguage->LanguageText('mod_userimport_progselec','userimport')."&nbsp;".$remoteProg."</b>");
        }
        print $this->objLanguage->LanguageText('mod_userimport_selectprogram','userimport');
        print $objForm2->show()."\n";
    } else {
        print "<br /><br />\n";
        print "<span class='noRecordsMessage'>".$this->objLanguage->LanguageText('mod_userimport_nodata2','userimport')."</span>\n";
    }
}


if (isset($modules)){
    if (is_array($modules)){
        $objModuleDrop= new dropdown('classmodule');
        $objForm3=new form('remoteimport',$this->uri(array('action'=>'remoteimport')));
        foreach ($modules as $line)
        {
            $objModuleDrop->addOption($line[0],htmlentities($line[1]));
        }
        $objModuleDrop->setSelected($this->getParam('classmodule',NULL));
        $objForm3->addToForm($objModuleDrop->show());
        $objForm3->addToForm($objButton->show());
        $hidden= new textinput('faculty',$this->getParam('faculty',NULL),'hidden');
        $objForm3->addToForm($hidden->show());
        $hidden= new textinput('program',$this->getParam('program',NULL),'hidden');
        $objForm3->addToForm($hidden->show());
        print $this->objLanguage->LanguageText('mod_userimport_selectmodule','userimport');
        print $objForm3->show()."\n";
    } else {
        print "<br /><br />\n";
        print "<span class='noRecordsMessage'>".$this->objLanguage->LanguageText('mod_userimport_nodata2','userimport')."</span>\n";
    }
}


if (isset($remoteCode)){
    print "<p />\n";
    if (is_array($classlist)){
        $classimport=$this->uri(array('action'=>'remoteclassimport','classmodule'=>$remoteCode));
        $xmldump=$this->uri(array('action'=>'remotexml','classmodule'=>$remoteCode));
        $linkButton1=new button('submit',$this->objLanguage->languageText('mod_userimport_exportxml','userimport'));
        print "<a href='$xmldump'>".$linkButton1->show()."</a>";
        $linkButton2=new button('submit',ucfirst(strtolower($this->objLanguage->code2Txt('mod_userimport_remote1','userimport',array('context'=>$this->contextCode)))));
        
        print "<a href='$classimport'>".$linkButton2->show()."</a>\n";
    
        $this->loadClass('htmltable','htmlelements');
        $objTable=new htmltable();
        $objTable->arrayToTable($classlist);
        if (isset($remoteDesc)){
            $objTable->caption="<b>".$this->objLanguage->LanguageText('mod_userimport_modselec','userimport')."&nbsp;$remoteDesc</b>";    
        }
        print $objTable->show()."\n";
        print "<a href='$xmldump'>".$linkButton1->show()."</a>";
        print "<a href='$classimport'>".$linkButton2->show()."</a>\n";
    } else {
        print "<br /><br />\n";
        print "<span class='noRecordsMessage'>".$this->objLanguage->LanguageText('mod_userimport_nodata1','userimport')."</span>\n";
    }
}



?>
