var xcourseid;
function loadFormCJS(){
    var args=loadFormCJS.arguments;
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

    var ds2a = new Ext.data.Store({
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

    var ds2b = new Ext.data.Store({
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

    var ds3 = new Ext.data.Store({
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

    var ds4a = new Ext.data.Store({
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

    var ds4b1 = new Ext.data.Store({
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

    var ds4b2 = new Ext.data.Store({
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

    var dataview1=new Ext.DataView({
        tpl: resultTpl,
        store: ds1
    });

    var dataview2a=new Ext.DataView({
        tpl: resultTpl,
        store: ds2a
    });

    var dataview2b=new Ext.DataView({
        tpl: resultTpl,
        store: ds2b
    });

    var dataview3=new Ext.DataView({
        tpl: resultTpl,
        store: ds3
    });

    var dataview4a=new Ext.DataView({
        tpl: resultTpl,
        store: ds4a
    });

    var dataview4b1=new Ext.DataView({
        tpl: resultTpl,
        store: ds4b1
    });

     var dataview4b2=new Ext.DataView({
        tpl: resultTpl,
        store: ds4b2
    });

    var textarea1=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea2a=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea2b=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea3=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea4a=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea4b1=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

    var textarea4b2=  new Ext.form.TextArea({
        width: 500,
        height: 100
    });

   CommentsPanel.override({ renderTo : 'question1comment' });

   commentsPanel1= new CommentsPanel(
    dataview1,
    textarea1,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds1.load({
        params:{
            courseid:args[0],
            formnumber:'C',
            question:'C1',
            comment: textarea1.getValue()
        }
        });
        textarea1.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question2acomment' });

    commentsPanel2a= new CommentsPanel(
    dataview2a,
    textarea2a,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds2a.load({
        params:{
            courseid:args[0],
            formnumber:'C',
            question:'C2a',
            comment: textarea2a.getValue()
        }
        });
        textarea2a.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question2bcomment' });

    commentsPanel2b= new CommentsPanel(
    dataview2b,
    textarea2b,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds2b.load({
        params:{
            courseid:args[0],
            formnumber:'C',
            question:'C2b',
            comment: textarea2b.getValue()
        }
        });
        textarea2b.setValue('');
        }
    })
    );


    CommentsPanel.override({ renderTo : 'question3comment' });

    commentsPanel3= new CommentsPanel(
    dataview3,
    textarea3,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds3.load({
        params:{
            courseid:args[0],
            formnumber:'C',
            question:'C3',
            comment: textarea3.getValue()
        }
        });
        textarea3.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question4acomment' });

    commentsPanel4a= new CommentsPanel(
    dataview4a,
    textarea4a,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds4a.load({
        params:{
            courseid:args[0],
            formnumber:'C',
            question:'C4a',
            comment: textarea4a.getValue()
        }
        });
        textarea4a.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question4b1comment' });

    commentsPanel4b1= new CommentsPanel(
    dataview4b1,
    textarea4b1,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds4b1.load({
        params:{
            courseid:args[0],
            formnumber:'C',
            question:'C4b1',
            comment: textarea4b1.getValue()
        }
        });
        textarea4b1.setValue('');
        }
    })
    );

    CommentsPanel.override({ renderTo : 'question4b2comment' });

    commentsPanel4b2= new CommentsPanel(
    dataview4b2,
    textarea4b2,
    new Ext.Button({
        text:'Save',
        handler: function(){

        ds4b2.load({
        params:{
            courseid:args[0],
            formnumber:'C',
            question:'C4b2',
            comment: textarea4b2.getValue()
        }
        });
        textarea4b2.setValue('');
        }
    })
    );

    ButtonPanel.override({ renderTo : 'question1comment' });
    new ButtonPanel(
        [{
            id: 'cbutton1',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton1').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton1').setIconClass('commentcollapse');
                    ds1.load({params:{courseid:xcourseid,formnumber:'C', question:'C1', comment: textarea1.getValue()}});
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

    ButtonPanel.override({renderTo : 'question2acomment'});
    new ButtonPanel(
        [{
            id: 'cbutton2a',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton2a').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton2a').setIconClass('commentcollapse');
                   ds2a.load({params:{courseid:xcourseid,formnumber:'C', question:'C2a', comment: textarea2a.getValue()}});
                    commentsPanel2a.expand();
                }else{
                    Ext.getCmp('cbutton2a').setIconClass('commentadd');
                    commentsPanel2a.collapse()
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

    ButtonPanel.override({ renderTo : 'question2bcomment' });
    new ButtonPanel(
        [{
            id: 'cbutton2b',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton2b').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton2b').setIconClass('commentcollapse');
                    ds2b.load({params:{courseid:xcourseid,formnumber:'C', question:'C2b', comment: textarea2b.getValue()}});
                    commentsPanel2b.expand();
                }else{
                    Ext.getCmp('cbutton2b').setIconClass('commentadd');
                   commentsPanel2b.collapse()
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

    ButtonPanel.override({ renderTo : 'question3comment' });
    new ButtonPanel(
        [{
            id: 'cbutton3',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton3').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton3').setIconClass('commentcollapse');
                    ds3.load({params:{courseid:xcourseid,formnumber:'C', question:'C3', comment: textarea3.getValue()}});
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

    ButtonPanel.override({ renderTo : 'question4acomment' });
    new ButtonPanel(
        [{
            id: 'cbutton4a',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton4a').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton4a').setIconClass('commentcollapse');
                    ds4a.load({params:{courseid:xcourseid,formnumber:'C', question:'C4a', comment: textarea4a.getValue()}});
                    commentsPanel4a.expand();
                }else{
                    Ext.getCmp('cbutton4a').setIconClass('commentadd');
                    commentsPanel4a.collapse()
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

    ButtonPanel.override({ renderTo : 'question4b1comment' });
    new ButtonPanel(
        [{
            id: 'cbutton4b1',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton4b1').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton4b1').setIconClass('commentcollapse');
                    ds4b1.load({params:{courseid:xcourseid,formnumber:'C', question:'C4b1', comment: textarea4b1.getValue()}});
                    commentsPanel4b1.expand();
                }else{
                    Ext.getCmp('cbutton4b1').setIconClass('commentadd');
                    commentsPanel4b1.collapse();
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

    ButtonPanel.override({ renderTo : 'question4b2comment' });
    new ButtonPanel(
        [{
            id: 'cbutton4b2',
            iconCls: 'commentadd',
            handler: function(){
                if(Ext.getCmp('cbutton4b2').iconCls == 'commentadd'){
                    Ext.getCmp('cbutton4b2').setIconClass('commentcollapse');
                    ds4b2.load({params:{courseid:xcourseid,formnumber:'C', question:'C4b2', comment: textarea4b2.getValue()}});
                    commentsPanel4b2.expand();
                }else{
                    Ext.getCmp('cbutton4b2').setIconClass('commentadd');
                    commentsPanel4b2.collapse();
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