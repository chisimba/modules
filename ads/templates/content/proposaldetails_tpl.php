<?php
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/session.css').'"/>';

$iconscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/icons.css').'"/>';
$rowcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/Ext.ux.grid.RowActions.css').'"/>';
$emptycss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/empty.css').'"/>';
$webpagecss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/webpage.css').'"/>';
$gridsearchcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/gridsearch.css').'"/>';

$webpagejs = '<script language="JavaScript" src="'.$this->getResourceUri('js/WebPage.js').'" type="text/javascript"></script>';
$themecombojs = '<script language="JavaScript" src="'.$this->getResourceUri('js/Ext.ux.ThemeCombo.js').'" type="text/javascript"></script>';
$iconmenujs = '<script language="JavaScript" src="'.$this->getResourceUri('js/Ext.ux.IconMenu.js').'" type="text/javascript"></script>';
$toastjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/Ext.ux.Toast.js').'" type="text/javascript"></script>';
$searchjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/Ext.ux.grid.Search.js').'" type="text/javascript"></script>';
$rowactionsjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/Ext.ux.grid.RowActions.js').'" type="text/javascript"></script>';
$gridsearchjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/gridsearch.js').'" type="text/javascript"></script>';
$styleSheet="
    <style type=\"text/css\">
        .x-check-group-alt {
            background: #D1DDEF;
            border-top:1px dotted #B5B8C8;
            border-bottom:1px dotted #B5B8C8;
        }
        div#myForm {
            padding: 0 3em;
        }
    </style>
    ";
$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);
$this->appendArrayVar('headerParams',$styleSheet);

$this->appendArrayVar('headerParams',$iconscss);
$this->appendArrayVar('headerParams',$rowcss);
$this->appendArrayVar('headerParams',$emptycss);
$this->appendArrayVar('headerParams',$webpagecss);
$this->appendArrayVar('headerParams',$gridsearchcss);

$this->appendArrayVar('headerParams',$webpagejs);
$this->appendArrayVar('headerParams',$themecombojs);
$this->appendArrayVar('headerParams',$iconmenujs);
$this->appendArrayVar('headerParams',$toastjs);
$this->appendArrayVar('headerParams',$searchjs);
$this->appendArrayVar('headerParams',$rowactionsjs);
$this->appendArrayVar('headerParams',$gridsearchjs);

$nextForm = new link($this->uri(array('action'=>'viewform','courseid'=>$this->id, 'formnumber'=>$this->allForms[0])));
$submitForm = new link($this->uri(array('action'=>'submitproposal','courseid'=>$this->id)));
$submitUrl = new link($this->uri(array('action'=>'sendProposal','edit'=>true,'id'=>$this->id)));
$addCommentUrl = new link($this->uri(array('action'=>'addcomment','courseid'=>$this->id)));
$saveCommentUrl = new link($this->uri(array('action'=>'savecomment','courseid'=>$this->id)));
$searchusers =$this->uri(array('action'=>'searchusers'));
$currentEditor=$this->objDocumentStore->getCurrentEditor($this->id);
$maxVersion=$this->objDocumentStore->getMaxVersion($this->id);
$lastEditDate=$this->objDocumentStore->getLastEditDate($this->id);
$status=$this->objDocumentStore->getStatus($this->id);
$iscurrentEdit=$this->objDocumentStore->isCurrentEditor($currentEditor);
$courseProposal=$this->objCourseProposals->getCourseProposal($this->id);
$saveCommentUrl = new link($this->uri(array('action'=>'savecomment','courseid'=>$this->id,'status'=>$courseProposal['status'])));
$comments= $this->objComment->getAllcomments($this->id);

$items = "{boxLabel: 'New', name: 'proposalstatus', inputValue: '0'";
if($courseProposal['status'] == 0) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'APO comment', name: 'proposalstatus', inputValue: '1'";
if($courseProposal['status'] == 1) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'Library comment', name: 'proposalstatus', inputValue: '2'";
if($courseProposal['status']== 2) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'Subsidy comment', name: 'proposalstatus', inputValue: '3'";
if($courseProposal['status'] == 3) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'Faculty committee', name: 'proposalstatus', inputValue: '4'";
if($courseProposal['status'] == 4) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'Faculty', name: 'proposalstatus', inputValue: '5'";
if($courseProposal['status'] == 5) {
    $items .= ", checked: true";
}
$items .= "},";
$items .= "{boxLabel: 'APDC', name: 'proposalstatus', inputValue: '6'";
if($courseProposal['status'] == 0) {
    $items .= ", checked: true";
}
$items .= "}";

$statuscodes=  array(
              "0"=> 'New',
              "1"=>'APO Comment',
              "2"=>'Library comment',
              "3"=>'Subsidy comment',
              "4"=>'Faculty subcommittee',
              "5"=>'Faculty',
              "6"=> 'APDC');
$mainjs=
"
    var wform = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        width:550,
        labelWidth: 135,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:'".str_replace("amp;", "", $expressLink)."',
        defaultType: 'textfield',
        renderTo: 'surface',
        collapsible: true,
        defaults: {width: 550},
        bodyStyle:'background-color:transparent',
        border:false,
        items: {
            xtype: 'fieldset',
            title: 'Course proposal details',
            autoHeight: true,
            items:[
               new Ext.form.DisplayField({
               fieldLabel: 'Current editor:',
               value: '".$currentEditor."'
               }),
               new Ext.form.DisplayField({
               fieldLabel: 'Last edit date:',
               value: '".$lastEditDate."'
               }),
               new Ext.form.DisplayField({
               fieldLabel: 'Status:',
               value: '".$statuscodes[$courseProposal['status']]."'
               }),
               new Ext.form.DisplayField({
               fieldLabel: 'Comments:',
               value: '<b>".$comments."</b>'
               })
             ]
          }
    });

var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',

        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
         url:'".str_replace("amp;", "", $saveCommentUrl->href)."',
        defaultType: 'textfield',
        items:[
        new Ext.form.TextArea({
            fieldLabel: 'Comments',
            name: 'commentField',
            id: 'commentsFieldId',
            width: 400,
            height: 200
          })
          ]

      });

   var radioGroup = {
   xtype: 'radiogroup',
   itemCls: 'x-check-group-alt',
   fieldLabel: 'Proposal Status',
   columns: 1,
   items: [$items]
    };

  var sform = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        url: 'index.php?module=ads&action=submitproposalstatus&id=".$courseProposal['id']."&version=".$maxVersion."',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        items:[
         radioGroup
          ]

      });


    var addCommentsWin;
    var searchUsersWin;
    var updateStatusWin;
    var button = Ext.get('action-btn');
//    button.on('click', function(){
function processActionDD(){
 var actiondd = document.getElementById('actiondd').value;

  if(actiondd == \"addcomment\"){
       if(!addCommentsWin){
            addCommentsWin = new Ext.Window({
                applyTo:'addcomments-win',
                layout:'fit',
                title:'Enter Comments',
                width:500,
                height:300,
                x:250,
                y:50,
                closeAction:'hide',
                plain: true,
                items: [
                form
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
                       addCommentsWin.hide();
                    }
                  }
                ]

            });
        }
       
        addCommentsWin.show(this);
      }

    if(actiondd == \"updatestatus\"){

       if(!updateStatusWin){
            updateStatusWin = new Ext.Window({
                applyTo:'status-win',
                layout:'fit',
                title:'Update Status',
                width:300,
                height:300,
                x:250,
                y:50,
                closeAction:'hide',
                plain: true,
                items: [
                sform
                ],
                  buttons: [{
                    text:'Save',
                    handler: function(){
                  if (sform.url){
                            sform.getForm().getEl().dom.action = sform.url;
                          }
                        sform.getForm().submit();

                  }
                  }
                  ,{
                    text: 'Cancel',
                    handler: function(){
                       updateStatusWin.hide();
                    }
                  }
                ]

            });
        }
        updateStatusWin.show(this);
      }//end if

    if(actiondd == \"editproposal\"){
      window.location.href = '".str_replace("amp;", "",$nextForm->href)."';
    }//end if

if(actiondd == \"forward\"){
showSearchWinX();
}//end if
};
";



$content.= "<script type=\"text/javascript\">".$mainjs."</script>";

$actionsDropDown = new dropdown ('actions');
$actionsDropDown->cssId = 'actiondd';

$actionsDropDown->addOption('default','Select action ...');
$actionsDropDown->addOption('editproposal','Edit Proposal');
$actionsDropDown->addOption('addcomment','Add Comment');
$actionsDropDown->addOption('updatestatus','Update Status');
$actionsDropDown->addOption('forward','Forward');
$actionsDropDown->addOption('forwardtomoderator','Forward to moderator');

$actionButton = new button('action','Take Action');
$actionButton->setId('action-btn');
$content = $message;
$renderSurface='
        <div id="addcomments-win" class="x-hidden">
        <div class="x-window-header">Add Comment</div>
        </div>

        <div id="status-win" class="x-hidden">
        <div class="x-window-header">Update Status</div>
        </div>

        <div id="search-xwin" class="x-hidden">
        <div class="x-window-header">Search</div>
        </div>

     
';
$content= '<div id="surface"><h1>'.$courseProposal['title'].'</h1>'.$renderSurface.'   </div>';
$content.= "<script type=\"text/javascript\">".$mainjs."</script>";
$actionsDropDown->addOnChange('processActionDD();');
$renderContent='<div>'.$actionsDropDown->show().'<br/>'.$content.'<div id="bbbbb">dsdss</div></div';

// Create an instance of the css layout class
$cssLayout = $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$rightSideColumn .= $renderContent;
$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');

$leftSideColumn = $nav->getLeftContent($toSelect, $this->getParam('action'), $this->id);
//$leftSideColumn = $postLoginMenu->show();
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>
