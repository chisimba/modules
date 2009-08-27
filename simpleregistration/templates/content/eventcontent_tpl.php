<?php
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');


$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/session.css').'"/>';
$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);

$eventcontent=array();
$mode="new";
if(count($content) > 0){
    $eventcontent=$content[0];
    $mode="edit";
}

$savecontentUrl = $this->uri(array('action'=>'savecontent','eventid'=>$eventid,'mode'=>$mode));
$homeUrl = $this->uri(array('action'=>'eventlisting'));
$mainjs="
Ext.onReady(function(){

var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        renderTo:'eventcontent',
        url:'".str_replace("amp;", "", $savecontentUrl)."',
        defaultType: 'textfield',
        items: [
        new Ext.form.TextArea({
        fieldLabel: 'Date/Time/Venue',
        value: '".$eventcontent['event_timevenue']."',
        width: 600,
        height: 200,
        name: 'venuefield'
        }),

        new Ext.form.TextArea({
        fieldLabel: 'Main Content',
        value: '".$eventcontent['event_content']."',
        width: 600,
        height: 300,
        name: 'contentfield'
       }),

       new Ext.form.TextArea({
        fieldLabel: 'Left Title1',
        width: 600,
        value: '".$eventcontent['event_lefttitle1']."',
        name: 'lefttitle1field'
       }),

        new Ext.form.TextArea({
        fieldLabel: 'Left Title2',
        value: '".$eventcontent['event_lefttitle2']."',
        width: 600,
        name: 'lefttitle2field'
       }),
        new Ext.form.TextArea({
        fieldLabel: 'Footer',
        value: '".$eventcontent['event_footer']."',
        width: 600,
        name: 'footerfield'
       }),
        new Ext.form.TextArea({
        fieldLabel: 'Email Contact',
        value: '".$eventcontent['event_emailcontact']."',
        width: 600,
        name: 'emailcontactfield'
       }),
        new Ext.form.TextArea({
        fieldLabel: 'Email Subject',
        value: '".$eventcontent['event_emailsubject']."',
        width: 600,
        name: 'emailsubjectfield'
       }),
        new Ext.form.TextArea({
        fieldLabel: 'Email Name',
        value: '".$eventcontent['event_emailname']."',
        width: 600,
        name: 'emailnamefield'
       }),
        new Ext.form.TextArea({
        fieldLabel: 'Email Content',
        value: '".$eventcontent['event_emailcontent']."',
        width: 600,
        name: 'emailcontentfield'
       }),
     new Ext.form.TextArea({
        fieldLabel: 'Email Attachments',
        value: '".$eventcontent['event_emailattachments']."',
        width: 600,
        name: 'emailattachmentfield'
       })
],
                  buttons: [{
                    text:'Save',
                    handler: function(){
                      if (form.url){
                      form.getForm().getEl().dom.action = form.url;
                       }
                     form.getForm().submit();
                   }
                   }
                  ,{
                    text: 'Cancel',
                    handler: function(){
                      window.location.href = '".str_replace("amp;", "",$homeUrl)."';
                    }
                  }
                ]
});
  });
";

$content= '<div id="eventcontent"><h1>'.$title.'</h1><br /><br /></div>';
$content.= "<script type=\"text/javascript\">".$mainjs."</script>";


// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$rightSideColumn .= $content;
$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent( $postLoginMenu->show());

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>
