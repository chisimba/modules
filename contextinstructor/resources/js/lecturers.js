var addMemberForm;
var userid="";
function initChangeInstructor(userlist,url){
    var userdatastore = new Ext.data.ArrayStore({
        fields: ['userid','name'],
        data : userlist
    });
    var userField = new Ext.form.ComboBox({
        store: userdatastore,
        displayField:'name',
        valueField: 'userid',
        fieldLabel:'Names',
        typeAhead: true,
        mode: 'local',
        editable:false,
        allowBlank:false,
        forceSelection: true,
        triggerAction: 'all',
        emptyText:'Select user...',
        selectOnFocus:true,
        hiddenName : 'userfield', listeners:{
            select: function(combo, record, index){
                userid= record.data.userid;
            }
        }

    });
    addMemberForm = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        labelWidth: 55,
        bodyStyle:'padding:5px 5px 0',
        standardSubmit: true,
        url:url,
        defaultType: 'textfield',
        items: userField

    });

}

function showSelectInstructorWin(){
    var addMemberWin;
    if(!addMemberWin){
        addMemberWin = new Ext.Window({
            applyTo:'addsession-win',
            layout:'fit',
            width:500,
            height:150,
            x:250,
            y:50,
            closeAction:'destroy',
            plain: true,

            items: addMemberForm,

            buttons: [{
                text:'Save',
                handler: function(){
                    if (addMemberForm.url){
                        addMemberForm.getForm().getEl().dom.action = addMemberForm.url;
                   
                    }
                    addMemberForm.getForm().submit();
                 //   window.location=addMemberForm.url+"&userid="+userid;
                }
            },{
                text: 'Close',
                handler: function(){
                    addMemberWin.hide();
                }
            }]
        });
    }
    addMemberWin.show(this);

}
