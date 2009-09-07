<?php
// load classes
$this->loadclass('link','htmlelements');
// scripts
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$maincss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/courseproposal.css').'"/>';


$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $maincss);


$editButton = new button('editBtn','Edit Proposal');
$editButton->setId('edit-btn');

$addCommentsButton = new button('add','Add Comments');
$addCommentsButton->setId('add-comments-btn');

$forwardButton = new button('forwardButton','Forward');
$forwardButton->setId('forward-btn');

$forwardToModeratorButton = new button('add','Forward To Moderator');
$forwardToModeratorButton->setId('forward-to-moderator-btn');

$content='';

$content.= '<div><div id="tree-div" style="padding-top: 2em;"></div>
          <div id="myLayoutx">
            <div id="addcomments-win" class="x-hidden"></div>
            <div id="send-win" class="x-hidden">
                <div class="x-window-header">Send Document To User</div>
            </div>
          </div><center>'.$editButton->show().'&nbsp;'.$addCommentsButton->show().'&nbsp;'.$forwardButton->show().'&nbsp;'.$forwardToModeratorButton->show().'</center></div>';
   //where we render the 'popup' window
$renderSurface='<div id="addcomments-win" class="x-hidden">
       <div class="x-window-header">Add Session</div>
        </div>';
$content= '<div id="tree-div"><div id="myLayoutx">'.$renderSurface.$addCommentsButton->show().'<br /><br /></div></div>';

// labels for the forward form (popup window)
$lname = "Last Name";
$fname = "First Name";
$email = "Email Address";
$phone = "Phone #";
$nextForm = new link($this->uri(array('action'=>'viewform','courseid'=>$this->id, 'formnumber'=>$this->allForms[0])));
$submitForm = new link($this->uri(array('action'=>'submitproposal','courseid'=>$this->id)));
$submitUrl = new link($this->uri(array('action'=>'sendProposal','edit'=>true,'id'=>$this->id)));
$addCommentUrl = new link($this->uri(array('action'=>'addcomment','courseid'=>$this->id)));
$saveCommentUrl = new link($this->uri(array('action'=>'savecomment','courseid'=>$this->id)));
$iscurrentEdit=$this->objDocumentStore->isCurrentEditor($this->objDocumentStore->getCurrentEditor($this->id));


$mainjs =  "

            Ext.onReady(function() {
                var tree = new Ext.tree.TreePanel({
                                useArrows: true,
                                autoScroll: true,
                                animate: true,
                                enableDD: true,
                                containerScroll: true,
                                border: false,
                                rootVisible: false,

                                // auto create TreeLoader
                                dataUrl: '".str_replace("amp;", "",$this->uri(array('action'=>'gethistorydata', 'courseid'=>$this->id, 'formnumber'=>$this->allforms[0])))."',
                                root: {
                                    nodeType: 'async',
                                    text: 'Ext JS',
                                    draggable: false,
                                    id: 'source'
                                },
                                listeners: {
                                    click: function(n) {
                                      //  populateForm(n);
                                    }
                                }

                            });
                tree.getRootNode().expand();

               var currentEditorField=new Ext.form.DisplayField({
                            fieldLabel: 'Current Editor',
                            name: 'currentEditor',
                            editable:false ,
                            id: 'cEditor'
                        });
                var form = {
                    xtype: 'form', // since we are not using the default 'panel' xtype, we must specify it
                    id: 'form-panel',
                    labelWidth: 75,
                    bodyStyle: 'padding:15px',
                    width: 600,
                    labelPad: 20,
                    defaultType: 'textfield',
                    items: [
                        new Ext.form.DisplayField({
                            fieldLabel: 'Date',
                            name: 'date',
                            id: 'myDate'
                            
                        })
                        ,new Ext.form.DisplayField({
                            fieldLabel: 'Edited by',
                            name: 'editor',
                            id: 'myEditor'
                        })
                        ,new Ext.form.DisplayField({
                            fieldLabel: 'Comments',
                            name: 'comments',
                            editable:false ,
                            id: 'myComments'
                        })
                    ]";
                   if($iscurrentEdit || $this->objUser->isAdmin()){
                   $mainjs.=", buttons: [
                        {
                            text: 'Edit Proposal',
                            handler: function()
                                editProposal()
                        },
                        {
                            text: 'Add Comment',
                            handler: function()
                                addComment()
                        },

                        {
                            text: 'Foward',
                            handler: function()
                                forwardProposal()
                        },
                        {
                            text: 'Forward to Moderator',
                            handler: function()
                                forwardProposalToModerator()
                        }
                    ]";
                   }
               $mainjs.=",
              
                };

                var propHist = {
                    title: 'Proposal History',
                    width: 250,
                    height: 300,
                    items: [tree]
                };

                var propForm = {
                    title: 'Proposal Form',
                    columnWidth: .95,
                    height: 300,

                    items: [
                            {
                            html:'',
                            width:600,
                            id: 'myHeading'
                            },
                           form
                           ]
                };

                new Ext.Viewport({
                    title: 'Table Layout',
                    applyTo: 'myLayoutx',
                    region: 'center',
                    layout: 'column',
                    miheight: 200,
                    defaults: {
                        bodyStyle:'padding:20px'
                    },
                    items: [propHist, propForm]
                });

                // populate the fields in proposal form with data
                jQuery.getJSON('".str_replace("amp;", "",$this->uri(array('action'=>'gethistorydata', 'courseid'=>$this->id, 'data'=>true)))."', function(json) {
                    jQuery('#myHeading').html('<h3>The current editor of this document is: ' + json.currentuser+'</h3>');
                    jQuery('#myDate').append(json.datemodified);
                    jQuery('#myEditor').append(json.editor);
                    jQuery('#myComments').append(json.comment);


                });
           });

           function populateForm(n) {
                // get the data clicked on
                var propChosen = n.attributes.text,
                    eachSplit = propChosen.split('_'),
                    date = eachSplit[0],
                    version = eachSplit[1],
                    url = '".str_replace("amp;", "",$this->uri(array('action'=>'gethistorydata', 'courseid'=>$this->id, 'data'=>true)))."';

                    url = url + '&version=' + version;

                // get data from database and populate form fields with query data
                jQuery.getJSON(url, function(json) {
                    var editor = json.editor,
                        comments = json.comment;

                    jQuery('#myDate').replaceWith(date);
                    jQuery('#myEditor').val(editor);
                    jQuery('#myComments').replaceWith(comments);
                });
           }

            function forwardProposal() {
                var win;
                //var button = Ext.get('show-btn');
                var myForm = new Ext.FormPanel({
                                labelWidth: 125,
                                url:'".str_replace("amp;", "", $submitUrl->show())."',
                                frame:true,
                                //title: 'User Details',
                                bodyStyle:'padding:5px 5px 0',
                                width: 350,
                                defaults: {width: 230},
                                defaultType: 'textfield',

                                items: [{
                                        fieldLabel: '".$lname."',
                                        name: 'lname',
                                        allowBlank: false
                                    },{
                                        fieldLabel: '".$fname."',
                                        name: 'fname',
                                        allowBlank: false
                                    },{
                                        fieldLabel: '".$email."',
                                        name: 'email',
                                        allowBlank: false
                                    },{
                                        fieldLabel: '".$phone."',
                                        name: 'phone',
                                        allowBlank: false
                                    }
                                ]
                            });

                // create the window on the first click and reuse on subsequent clicks
                if(!win){
                    win = new Ext.Window({
                        applyTo:'send-win',
                        layout:'fit',
                        width:500,
                        height:300,
                        closeAction:'hide',
                        plain: true,
                        items: myForm,
                        buttons: [{
                            text:'Submit',
                            handler: function() {
                                if (myForm.getForm().isValid()) {
                                    if (myForm.url) {
                                        //myForm.getForm().getEl().dom.action = myForm.url;
                                        //test = myForm.getForm().submit();
                                        win.hide();
                                        window.location.reload();
                                        Ext.MessageBox.alert('sending info');
                                    }
                                }
                            }
                        },{
                            text: 'Close',
                            handler: function(){
                                win.hide();
                            }
                        }]
                    });
                }
                win.show(this);
            }

            function forwardProposalToModerator() {
                window.location.href = '".str_replace("amp;", "", $submitForm->href)."';
            }

            function editProposal() {
                window.location.href = '".str_replace("amp;", "",$nextForm->href)."';
            }

            function addComment(){
            window.location.href = '".str_replace("amp;", "",$addCommentUrl->href)."';
           }

 var commentsform = new Ext.FormPanel({
    
    url:'".str_replace("amp;", "", $saveCommentUrl->href)."',
    id: 'form-panel',
    labelWidth: 75,
    bodyStyle: 'padding:15px',
    width: 600,
    labelPad: 20,
    defaultType: 'textfield',
    items: [
         new Ext.form.TextArea({
                            fieldLabel: 'Comments',
                            name: 'commentsField',
                            id: 'commentsFieldId',
                            width: 350,
                            height: 250
                        })
           ]
               
     });

    var addCommentsWin;
    var button = Ext.get('add-comments-btn');
    button.on('click', function(){

       if(!addCommentsWin){
            addCommentsWin = new Ext.Window({
                applyTo:'addcomments-win',
                layout:'fit',
                title:'Enter Comments',
                width:500,
                height:350,
                x:250,
                y:50,
                closeAction:'hide',
                plain: true,

               items:[
                      commentsform
                     ],

                  buttons: [{
                    text:'Save',
                    handler: function(){

                   if (commentsform.url){
                            commentsform.getForm().getEl().dom.action = commentsform.url;
                          }
                        commentsform.getForm().submit();

                   }}

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
});
";
   

$content.="<script type=\"text/javascript\">".$mainjs."</script>";
echo $content;

?>