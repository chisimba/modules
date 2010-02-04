function initRadioButtons(
    _type,
    _isReflection,
    _allowMultiple,
    canChangeField
)
{
    var isOnline = _type=='0';
    var isReflection = _isReflection=="1";
    var allowMultiple = _allowMultiple=="1";
    customPanel = Ext.extend(Ext.Panel, {
        id:'customPanel',
        border:false,
        constructor: function(radiobuttongroup){
            var items = [radiobuttongroup];
            customPanel.superclass.constructor.call(this, {
                items: items
            });
        }
    });
    if (canChangeField) {
        customPanel.override({
            renderTo : '_type'
        });
        var panel1 = new customPanel(
        {
            defaultType: 'radio',
            border:false,
            width:100,
            items: [
            {
                checked: isOnline,
                boxLabel: 'Online',
                name: 'type',
                inputValue: '0'
            }, {
                fieldLabel: '',
                checked: !isOnline,
                labelSeparator: '',
                boxLabel: 'Upload',
                name: 'type',
                inputValue: '1'
            }
            ]
        }
        );
    }
    customPanel.override({
        renderTo : 'isReflection'
    });
    var panel2 = new customPanel(
    {
        defaultType: 'radio',
        border:false,
        width:100,
        items: [
        {
            checked: isReflection,
            boxLabel: 'Yes',
            name: 'assesment_type',
            inputValue: '1'
        }, {
            fieldLabel: '',
            checked: !isReflection,
            labelSeparator: '',
            boxLabel: 'No',
            name: 'assesment_type',
            inputValue: '0'
        }
        ]
    }
    );
    customPanel.override({
        renderTo : 'allowMultiple'
    });
    var panel3 = new customPanel(
    {
        defaultType: 'radio',
        border:false,
        width:100,
        items: [
        {
            checked: allowMultiple,
            boxLabel: 'Yes',
            name: 'resubmit',
            inputValue: '1'
        }, {
            fieldLabel: '',
            checked: !allowMultiple,
            labelSeparator: '',
            boxLabel: 'No',
            name: 'resubmit',
            inputValue: '0'
        }
        ]
    }
    );
}