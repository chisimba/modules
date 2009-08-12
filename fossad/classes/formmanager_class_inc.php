<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of formmanager_class_inc
 *
 * @author david
 */
class formmanager extends object{

    public function init(){
        // scripts
        $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
        $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
        $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
        $maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/fossad.css').'"/>';

        $this->appendArrayVar('headerParams', $extbase);
        $this->appendArrayVar('headerParams', $extalljs);
        $this->appendArrayVar('headerParams', $extallcss);
        $this->appendArrayVar('headerParams', $maincss);
        $this->loadclass('link','htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
    }

    public function getLeftContent(){
        
        
        
        $sponsorA='<a href=""><img  width="100" height="50" src="'.$this->getResourceUri('images/inwent.gif').'"></a>';
        $sponsorB='<a href=""><img  width="100" height="50" src="'.$this->getResourceUri('images/sun.jpeg').'"></a>';
        $sponsorC='<a href=""><img  width="100" height="50" src="'.$this->getResourceUri('images/acer.jpeg').'"></a>';
        $sponsorD='<a href=""><img  width="100" height="50" src="'.$this->getResourceUri('images/hp.jpeg').'"></a>';

        $list=array(
            
            "0"=>$sponsorA,
            "1"=>$sponsorB,
            "2"=>$sponsorC,
            "3"=>$sponsorD,
        );
        $desc=
        '<ul id="nav-secondary">';
        $cssClass = '';
        foreach($list as $element){
             if(strtolower($element) == strtolower($toSelect)) {
                    $cssClass = ' class="active" ';
             }
            $desc.='<li $cssClass>'.$element.'</li>';
        }
        $desc.='</ul>';
        return $desc;
    }
    public function createRegisterForm(){
        $submitUrl = $this->uri(array('action' => 'register'));
        $regFormJS=
    "Ext.onReady(function(){

    Ext.QuickTips.init();

    /*
     * ================  Registration form  =======================
     */
  
   var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 75,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:'".str_replace("amp;", "", $submitUrl)."',
        defaultType: 'textfield',
        renderTo: 'registration',
        defaults: {width: 230},
        width: 350,
        bodyStyle:'background-color:transparent',
        border:false,

        items: [{
                fieldLabel: 'First Name',
                name: 'firstname',
                allowBlank:false
            },{
                fieldLabel: 'Last Name',
                name: 'lastname',
                allowBlank:false
            },{
                fieldLabel: 'Company',
                name: 'company'
            }, {
                fieldLabel: 'Email',
                name: 'emailfield',
                vtype:'email'
            }
        ],

        buttons: [{
            text: 'Sign Up',
            handler: function(){
            if (form.url)
            form.getForm().getEl().dom.action = form.url;
            form.getForm().submit();
            }
        }]
    });
    var xform = new Ext.FormPanel({
        labelWidth: 75, 
        url:'".str_replace("amp;", "", $submitUrl)."',
        frame:false,
        
        bodyStyle:'padding:5px 5px 0',
        width: 350,
        defaults: {width: 230},
        defaultType: 'textfield',
        bodyStyle:'background-color:transparent',
        border:false,

        items: [{
                fieldLabel: 'First Name',
                name: 'firstname',
                allowBlank:false
            },{
                fieldLabel: 'Last Name',
                name: 'lastname',
                allowBlank:false
            },{
                fieldLabel: 'Company',
                name: 'company'
            }, {
                fieldLabel: 'Email',
                name: 'emailfield',
                vtype:'email'
            }
        ],

        buttons: [{
            text: 'Sign Up',
            handler: function(){
            if (form.url)
            form.getForm().getEl().dom.action = form.url;
            form.getForm().submit();
            }
        }]
    });
 });
";

          //where we render the 'popup' window
        $content='<div id="registration"></div>';
        $content.= "<script type=\"text/javascript\">".$regFormJS."</script>";


        return $content;


    }
}
?>
