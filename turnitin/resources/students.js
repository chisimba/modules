
Ext.onReady(function(){
var form = new Ext.FormPanel({
    title: 'Simple Form with FieldSets',
    labelWidth: 75, // label settings here cascade unless overridden

    url: 'save-form.php',
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    width: 700,
    renderTo: document.body,
    layout:'column', // arrange items in columns

    defaults: {      // defaults applied to items

        layout: 'form',
        border: false,
        bodyStyle: 'padding:4px'
    },
    items: [{
        // Fieldset in Column 1

        xtype:'fieldset',
        columnWidth: 0.5,
        title: 'Fieldset 1',
        collapsible: true,
        autoHeight:true,
        defaults: {
            anchor: '-20' // leave room for error icon

        },
        defaultType: 'textfield',
        items :[{
                fieldLabel: 'Field 1'
            }, {
                fieldLabel: 'Field 2'
            }, {
                fieldLabel: 'Field 3'
            }
        ]
    },{
        // Fieldset in Column 2 - Panel inside

        xtype:'fieldset',
        title: 'Show Panel', // title, header, or checkboxToggle creates fieldset header

        autoHeight:true,
        columnWidth: 0.5,
        checkboxToggle: true,
        collapsed: true, // fieldset initially collapsed

        layout:'anchor',
        items :[{
            xtype: 'panel',
            anchor: '100%',
            title: 'Panel inside a fieldset',
            frame: true,
            height: 100
        }]
    }]
});
});