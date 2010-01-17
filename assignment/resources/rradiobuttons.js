function initRadioButtons(
    assgtype,
    reflection,
    resubmit
    ){

    var onlineval=assgtype == "0"?true:false;
    var uploadval=assgtype == "1"?true:false;
    if(onlineval == false && uploadval == false){
        onlineval=true;
    }
    var yesreflection=reflection=="1"?true:false;
    var noreflection=reflection=="0"?true:false;

    var yessubmit=resubmit=="1"?true:false;
    var nosubmit=resubmit=="0"?true:false;

    if(yessubmit == false && nosubmit == false){
        nosubmit=true;
    }

    if(yesreflection == false && noreflection == false){
        noreflection=true;
    }
    RRadioPanel = Ext.extend(Ext.Panel, {
        id:'radiopanelm',
        border:false,
       
        constructor: function(rbuttongroup){
            var items = [rbuttongroup];
            RRadioPanel.superclass.constructor.call(this, {
                items: items
            });
        }
    });


    RRadioPanel.override({
        renderTo : 'assgtype'
    });

    var assType= new RRadioPanel(
    {
        defaultType: 'radio',
        border:false,
        width:100,
        items: [
        {
            checked: onlineval,
            boxLabel: 'Online',
            name: 'type',
            inputValue: '0'
        }, {
            fieldLabel: '',
            checked: uploadval,
            labelSeparator: '',
            boxLabel: 'Upload',
            name: 'type',
            inputValue: '1'
        }
        ]
    }
    );
    RRadioPanel.override({
        renderTo : 'reflection'
    });
    var reflectionG= new RRadioPanel(
    {
        defaultType: 'radio',
       border:false,
        width:100,
        items: [
        {
            checked: yesreflection,
            boxLabel: 'Yes',
            name: 'assesment_type',
            inputValue: '1'
        }, {
            fieldLabel: '',
            checked: noreflection,
            labelSeparator: '',
            boxLabel: 'No',
            name: 'assesment_type',
            inputValue: '0'
        }
        ]
    }
    );


    RRadioPanel.override({
        renderTo : 'submissions'
    });
    var sub= new RRadioPanel(
    {
        defaultType: 'radio',
       border:false,
        width:100,
        items: [
        {
            checked: yessubmit,
            boxLabel: 'Yes',
            name: 'resubmit',
            inputValue: '1'
        }, {
            fieldLabel: '',
            checked: nosubmit,
            labelSeparator: '',
            boxLabel: 'No',
            name: 'resubmit',
            inputValue: '0'
        }
        ]
    }
    );
}