<?
        $this->objLanguage =  $this->getObject('language', 'language');
        $this->objUser =  $this->getObject('user', 'security');
        $this->objConfig =  $this->getObject('altconfig', 'config');
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objPopup = &$this->loadClass('windowpop', 'htmlelements');
        $objHeading->type = 2;
        $objHeading->align = 'center';
        $objHeading->str = $this->objConfig->getSiteName()." ".ucwords($this->objLanguage->code2Txt('mod_modulecatalogue_newupdates', 'modulecatalogue', NULL, 'latest updates'));
        $objUtils = $this->getObject('activityutilities', 'activitystreamer');
        $objSysConfig  = $this->getObject('altconfig','config');
        
        //Ext stuff
        $ext =$this->getJavaScriptFile('ext-3.0-rc2/adapter/ext/ext-base.js', 'htmlelements');
        $ext .=$this->getJavaScriptFile('ext-3.0-rc2/ext-all.js', 'htmlelements');
        $ext .=$this->getJavaScriptFile('search.js', 'activitystreamer');
        $ext .=$this->getJavaScriptFile('ext-3.0-rc2/examples/shared/examples.js', 'htmlelements');
       
        $ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css', 'htmlelements').'" type="text/css" />';
        $ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('ext-3.0-rc2/examples/grid/grid-example.css', 'htmlelements').'" type="text/css" />';
        $ext .= '<link rel="stylesheet" href="'.$this->getResourceUri('ext-3.0-rc2/examples/shared/examples.css', 'htmlelements').'" type="text/css" />';
        $this->appendArrayVar('headerParams', $ext);
        
        $this->appendArrayVar('headerParams', '
        	<script type="text/javascript">
        		var uri = "'.str_replace('&amp;','&',$this->uri(array('action' => 'jsonlistactivities', 'module' => 'activitystreamer'))).'"; 
        		var baseuri = "'.$objSysConfig->getsiteRoot().'index.php"; </script>');
								//Div to render content
        $str = '<div id="activity-topic-grid"></div>';
        $objPopup = new windowpop();
        $objPopup->set('location', $this->uri(array(
            'action' => 'singlereflection',
            'reflectId' => $item['id']
        ) , 'eportfolio'));
        $objPopup->set('linktext', $commentIcon);
        $objPopup->set('width', '600');
        $objPopup->set('height', '350');
        $objPopup->set('left', '200');
        $objPopup->set('top', '200');
        $objPopup->set('scrollbars', 'yes');
        $objPopup->set('resizable', 'yes');
        $objPopup->putJs(); // you only need to do this once per page
        //echo $objPopup->show();
        
        echo $objHeading->show().$str;
?>
