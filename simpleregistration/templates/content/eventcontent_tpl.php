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
$previewLink = new link($this->uri(array('action'=>'showevent','id'=>$eventid)));
$previewLink->link=$title;
$homeUrl = $this->uri(array('action'=>'eventlisting'));
$order   = array("\r\n", "\n", "\r");
$replace ='<br />';

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

        new Ext.form.HtmlEditor({
        fieldLabel: 'Date/Time/Venue',
        value: '".str_replace($order, $replace, $eventcontent['event_timevenue'])."',
        width: 900,
        height: 200,
        name: 'venuefield'
        }),

        new Ext.form.HtmlEditor({
        fieldLabel: 'Main Content',
        value: '".str_replace($order, $replace, $eventcontent['event_content'])."',
        width: 900,
        height: 300,
        name: 'contentfield'
       }),

       new Ext.form.HtmlEditor({
        fieldLabel: 'Left Title1',
        width: 900,
        value: '".str_replace($order, $replace,$eventcontent['event_lefttitle1'])."',
        name: 'lefttitle1field'
       }),

        new Ext.form.HtmlEditor({
        fieldLabel: 'Left Title2',
        value: '".str_replace($order, $replace,$eventcontent['event_lefttitle2'])."',
        width: 900,
        name: 'lefttitle2field'
       }),
        new Ext.form.HtmlEditor({
        fieldLabel: 'Footer',
        value: '".str_replace($order, $replace,$eventcontent['event_footer'])."',
        width: 900,
        name: 'footerfield'
       }),
        new Ext.form.HtmlEditor({
        fieldLabel: 'Email Contact',
        value: '".str_replace($order, $replace,$eventcontent['event_emailcontact'])."',
        width: 900,
        name: 'emailcontactfield'
       }),
        new Ext.form.HtmlEditor({
        fieldLabel: 'Email Subject',
        value: '".str_replace($order, $replace,$eventcontent['event_emailsubject'])."',
        width: 900,
        name: 'emailsubjectfield'
       }),
        new Ext.form.HtmlEditor({
        fieldLabel: 'Email Name',
        value: '".str_replace($order, $replace,$eventcontent['event_emailname'])."',
        width: 900,
        name: 'emailnamefield'
       }),
        new Ext.form.HtmlEditor({
        fieldLabel: 'Email Content',
        value: '".str_replace($order, $replace,$eventcontent['event_emailcontent'])."',
        width: 900,
        name: 'emailcontentfield'
       }),
     new Ext.form.HtmlEditor({
        fieldLabel: 'Email Attachments',
        value: '".str_replace($order, $replace,$eventcontent['event_emailattachments'])."',
        width: 900,
        name: 'emailattachmentfield'
       }),
   new Ext.form.TextField({
        fieldLabel: 'Show Staff Registration',
        value: '".str_replace($order, $replace,$eventcontent['event_staffreg'])."',
        width: 100,
        name: 'staffregfield'
       }),
   new Ext.form.TextField({
        fieldLabel: 'Show Visitor Registration',
        value: '".str_replace($order, $replace,$eventcontent['event_visitorreg'])."',
        width: 100,
        name: 'visitorregfield'
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

$content= '<div id="eventcontent"><h1>'.$previewLink->show().'</h1><br /><br /></div>';
$content.= "<script type=\"text/javascript\">".$mainjs."</script>";



// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(1);

$rightSideColumn .= $content;
$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent( $postLoginMenu->show());

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>
