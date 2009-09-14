
var xcourseid;
function loadFormDJS(){
    var args=loadFormDJS.arguments;
    xcourseid=args[0];
    Ext.QuickTips.init();
    ButtonPanel = Ext.extend(Ext.Panel, {
            
        layout:'table',
        defaultType: 'button',
        baseCls: 'x-plain',
        cls: 'btn-panel',
        menu: undefined,
        split: true,

        constructor: function(buttons){
            for(var i = 0, b; b = buttons[i]; i++){
                b.menu = this.menu;
                b.enableToggle = this.enableToggle;
                b.split = this.split;
                b.arrowAlign = this.arrowAlign;
            }
            var items = buttons;

            ButtonPanel.superclass.constructor.call(this, {
                items: items
            });
        }
    });



        CommentsPanel = Ext.extend(Ext.Panel, {
      
        id:'commentspanel',
        collapsible:true,
        autoScroll:true,
        border:false,
        collapsed:true,
        textarea:null,
        buttonAlign:'left',
        constructor: function(dataview,textarea,button1){
           
            var items = [dataview,textarea,button1];

            CommentsPanel.superclass.constructor.call(this, {
                items: items
            });
        }});

 var resultTpl = new Ext.XTemplate(
        '<tpl for=".">',
        '<font  color="green"><div class="search-item">',
        '<b>{names}:{commentdate}</b><br/>',
        '<span>{comment}</span></font>',
        '</div></tpl>'
        );

 var ds1 = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url:location.href+"?module=ads&action=addquestioncomment"
    }),
    reader: new Ext.data.JsonReader({
        root: 'rows',
        totalProperty: 'totalCount',
        id: 'qid'
    }, [
        {name: 'names',mapping: 'names'},
        {name: 'commentdate', mapping: 'commentdate'},
        {name: 'comment', mapping: 'comment'}
    ])

});

 var ds2 = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url:location.href+"?module=ads&action=addquestioncomment"
    }),
    reader: new Ext.data.JsonReader({root: 'rows',totalProperty: 'totalCount',id: 'qid'}, 
       [
        {name: 'names',mapping: 'names'},
        {name: 'commentdate',mapping: 'commentdate'},
        {name: 'comment',mapping: 'comment'}
       ]

 )});

  var ds3 = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url:location.href+"?module=ads&action=addquestioncomment"
    }),
    reader: new Ext.data.JsonReader({root: 'rows',totalProperty: 'totalCount',id: 'qid'},
       [
        {name: 'names',mapping: 'names'},
        {name: 'commentdate',mapping: 'commentdate'},
        {name: 'comment',mapping: 'comment'}
       ]

 )});
   var ds4 = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url:location.href+"?module=ads&action=addquestioncomment"
    }),
    reader: new Ext.data.JsonReader({root: 'rows',totalProperty: 'totalCount',id: 'qid'},
       [
        {name: 'names',mapping: 'names'},
        {name: 'commentdate',mapping: 'commentdate'},
        {name: 'comment',mapping: 'comment'}
       ]

 )});
   var ds5 = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url:location.href+"?module=ads&action=addquestioncomment"
    }),
    reader: new Ext.data.JsonReader({root: 'rows',totalProperty: 'totalCount',id: 'qid'},
       [
        {name: 'names',mapping: 'names'},
        {name: 'commentdate',mapping: 'commentdate'},
        {name: 'comment',mapping: 'comment'}
       ]

 )});
    var ds6 = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url:location.href+"?module=ads&action=addquestioncomment"
    }),
    reader: new Ext.data.JsonReader({root: 'rows',totalProperty: 'totalCount',id: 'qid'},
       [
        {name: 'names',mapping: 'names'},
        {name: 'commentdate',mapping: 'commentdate'},
        {name: 'comment',mapping: 'comment'}
       ]

 )});
    var ds7 = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url:location.href+"?module=ads&action=addquestioncomment"
    }),
    reader: new Ext.data.JsonReader({root: 'rows',totalProperty: 'totalCount',id: 'qid'},
       [
        {name: 'names',mapping: 'names'},
        {name: 'commentdate',mapping: 'commentdate'},
        {name: 'comment',mapping: 'comment'}
       ]

 )});
    var dataview1=new Ext.DataView({
        tpl: resultTpl,
        store: ds1
       });
    var dataview2=new Ext.DataView({
        tpl: resultTpl,
        store: ds2
       });
    var dataview3=new Ext.DataView({
        tpl: resultTpl,
        store: ds3
       });
    var dataview4=new Ext.DataView({
        tpl: resultTpl,
        store: ds4
       });

    var dataview5=new Ext.DataView({
        tpl: resultTpl,
        store: ds5
       });

    var dataview6=new Ext.DataView({
        tpl: resultTpl,
        store: ds6
       });
    var dataview7=new Ext.DataView({
        tpl: resultTpl,
        store: ds7
       });
    var textarea1=  new Ext.form.TextArea({
     width: 500,
     height: 100
     
    });

    var textarea2=  new Ext.form.TextArea({
    width: 500,
    height: 100
    });

    var textarea3=  new Ext.form.TextArea({
    width: 500,
    height: 100
    });
    
    var textarea4=  new Ext.form.TextArea({
    width: 500,
    height: 100
    });
    
    var textarea5=  new Ext.form.TextArea({
    width: 500,
    height: 100
    });
    var textarea6=  new Ext.form.TextArea({
    width: 500,
    height: 100
    });
    var textarea7=  new Ext.form.TextArea({
    width: 500,
    height: 100
    });

    CommentsPanel.override({
    renderTo : 'question1comment'
    
    });
  
   commentsPanel1= new CommentsPanel(
    dataview1,
    textarea1,
    new Ext.Button({
        text:'Save',
        handler: function(){
      
        ds1.load({
        params:{
            courseid:args[0],
            formnumber:'D',
            question:'D1',
            comment: textarea1.getValue()
        }
        });
        textarea1.setValue('');
        }
    })
    );

 CommentsPanel.override({
  renderTo : 'question2comment'
  
    });

  commentsPanel2= new CommentsPanel(
  dataview2,
  textarea2,
  new Ext.Button({
        text:'Save',
        handler: function(){

        ds2.load({
        params:{
            courseid:args[0],
            formnumber:'D',
            question:'D2',
            comment: textarea2.getValue()
        }
        });
        textarea2.setValue('');
        }
    })
  );

CommentsPanel.override({
  renderTo : 'question3comment'
 });

commentsPanel3= new CommentsPanel(
  dataview3,
  textarea3,
    new Ext.Button({
        text:'Save',
        handler: function(){
            ds3.load({ params:{courseid:args[0],formnumber:'D',question:'D3', comment: textarea3.getValue()
             }

        });
        textarea3.setValue('');
        }
    })
  );


 CommentsPanel.override({
  renderTo : 'question4comment'
 });

commentsPanel4= new CommentsPanel(
  dataview4,
  textarea4,
    new Ext.Button({
        text:'Save',
        handler: function(){
            ds4.load({ params:{courseid:args[0],formnumber:'D',question:'D4', comment: textarea4.getValue()
             }

        });
        textarea4.setValue('');
        }
    })
  );

 CommentsPanel.override({
  renderTo : 'question5comment'
 });

  commentsPanel5= new CommentsPanel(
  dataview5,
  textarea5,
    new Ext.Button({
        text:'Save',
        handler: function(){
            ds5.load({ params:{courseid:args[0],formnumber:'D',question:'D5', comment: textarea5.getValue()
             }

        });
        textarea5.setValue('');
        }
    })
  );
  commentsPanel6= new CommentsPanel(
  dataview6,
  textarea6,
    new Ext.Button({
        text:'Save',
        handler: function(){
            ds6.load({ params:{courseid:args[0],formnumber:'D',question:'D6', comment: textarea6.getValue()
             }

        });
        textarea6.setValue('');
        }
    })
  );
  commentsPanel7= new CommentsPanel(
  dataview7,
  textarea7,
    new Ext.Button({
        text:'Save',
        handler: function(){
            ds7.load({ params:{courseid:args[0],formnumber:'D',question:'D7', comment: textarea7.getValue()
             }

        });
        textarea7.setValue('');
        }
    })
  );
 



    ButtonPanel.override({
        renderTo : 'question1comment'
    });
    new ButtonPanel(
        [{
            id: 'cbutton1',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton1').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton1').setIconClass('commentcollapse');
                    //reload start
                   ds1.load({params:{courseid:xcourseid,formnumber:'D', question:'D1', comment: textarea1.getValue()}});
                    //reload end
                    commentsPanel1.expand();
                }else{
                    Ext.getCmp('cbutton1').setIconClass('commentadd');
                   commentsPanel1.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }

        ]
        );



    ButtonPanel.override({
        renderTo : 'question2comment'
    });
    new ButtonPanel(
        [{
            id: 'cbutton2',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton2').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton2').setIconClass('commentcollapse');
                   ds2.load({params:{courseid:xcourseid,formnumber:'D', question:'D2', comment: textarea2.getValue()}});
                    commentsPanel2.expand();
                }else{
                    Ext.getCmp('cbutton2').setIconClass('commentadd');
                    commentsPanel2.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }

        ]
        );


    ButtonPanel.override({
        renderTo : 'question3comment'
    });
    new ButtonPanel(
        [{
            id: 'cbutton3',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton3').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton3').setIconClass('commentcollapse');
                    ds3.load({params:{courseid:xcourseid,formnumber:'D', question:'D3', comment: textarea3.getValue()}});
                    commentsPanel3.expand();
                }else{
                    Ext.getCmp('cbutton3').setIconClass('commentadd');
                    commentsPanel3.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }

        ]
        );

    ButtonPanel.override({
        renderTo : 'question4comment'
    });
    new ButtonPanel(
        [{
            id: 'cbutton4',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton4').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton4').setIconClass('commentcollapse');
               ds4.load({params:{courseid:xcourseid,formnumber:'D', question:'D4', comment: textarea3.getValue()}});
                    commentsPanel4.expand();
                }else{
                    Ext.getCmp('cbutton4').setIconClass('commentadd');
                    commentsPanel4.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }

        ]
        );
    ButtonPanel.override({
        renderTo : 'question5comment'
    });
    new ButtonPanel(
        [{
            id: 'cbutton5',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton5').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton5').setIconClass('commentcollapse');
                    ds5.load({params:{courseid:xcourseid,formnumber:'D', question:'D5', comment: textarea3.getValue()}});
                    commentsPanel5.expand();
                }else{
                    Ext.getCmp('cbutton5').setIconClass('commentadd');
                    commentsPanel5.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }

        ]
        );
    ButtonPanel.override({
        renderTo : 'question6comment'
    });
    new ButtonPanel(
        [{
            id: 'cbutton6',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton6').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton6').setIconClass('commentcollapse');
                    ds6.load({params:{courseid:xcourseid,formnumber:'D', question:'D6', comment: textarea3.getValue()}});
                    commentsPanel6.expand();
                }else{
                    Ext.getCmp('cbutton6').setIconClass('commentadd');
                    commentsPanel6.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }

        ]
        );
            ButtonPanel.override({
        renderTo : 'question7comment'
    });
    new ButtonPanel(
        [{
            id: 'cbutton7',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton7').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton7').setIconClass('commentcollapse');
                    ds7.load({params:{courseid:xcourseid,formnumber:'D', question:'D7', comment: textarea3.getValue()}});
                    commentsPanel7.expand();
                }else{
                    Ext.getCmp('cbutton7').setIconClass('commentadd');
                    commentsPanel7.collapse()
                }
            }
        },
        {
            iconCls: 'help',
            handler: function(){

            }
        }

        ]
        );
}

