<?php
//language stuff
$session="Session Name";

// scripts
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';

    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
    $this->appendArrayVar('headerParams', $extalldebug);

    // Create an instance of the css layout class
    $cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
    $cssLayout->setNumColumns(2);

    $postLoginMenu  = $this->newObject('postloginmenu','toolbar');
    $cssLayout->setLeftColumnContent($postLoginMenu->show());
    $rightSideColumn =  '<div id="courseProposal"></div>';
    $cssLayout->setMiddleColumnContent($rightSideColumn);

    echo $cssLayout->show();



    $submitUrl = $this->uri(array('action'=>'savecourseproposal'));
    $cancelUrl = $this->uri(array('action'=>'NULL'));

    $script = "

Ext.apply(Ext.form.VTypes, {
    daterange : function(val, field) {
        var date = field.parseDate(val);

        if(!date){
            return;
        }
        if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
            var start = Ext.getCmp(field.startDateField);
            start.setMaxValue(date);
            start.validate();
            this.dateRangeMax = date;
        }
        else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
            var end = Ext.getCmp(field.endDateField);
            end.setMinValue(date);
            end.validate();
            this.dateRangeMin = date;
        }
        /*
         * Always return true since we're only using this vtype to set the
         * min/max allowed values (these are tested for after the vtype test)
         */
        return true;
   } });

  var dtTest = new Ext.form.DateField({
        name: 'brokenDateField',
        width: 390,
        allowBlank: false
    });

    Ext.onReady(function(){
        Ext.QuickTips.init();
       var simple = new Ext.FormPanel({
            standardSubmit: true,
            labelWidth: 125, // label settings here cascade unless overridden
            url:'".str_replace("amp;", "", $submitUrl)."',
            frame:true,
            title: 'Add  New Session',
            bodyStyle:'padding:5px 5px 0',
            width: 550,
            defaults: {width: 230},
            defaultType: 'textfield',

            items: [{
                    fieldLabel: '".$session."',
                    name: 'faculty',
                    allowBlank: false
                },
                    {
                    fieldLabel: 'Date',
                    name: 'ffaculty',
                    vtype: dtTest,
                    allowBlank: false
                }
            ],

            buttons: [{
                text: 'Save',
                handler: function(){
                    if (simple.getForm().isValid()) {
                        if (simple.url)
                            simple.getForm().getEl().dom.action = simple.url;

                        simple.getForm().submit();
                    }
                }
            },{
                text: 'Reset',
                handler: function(){
                    simple.getForm().reset();
                }
            }]
        });

        simple.render('courseProposal');
    });";

$dateRangeScript="
<script language=\"javascript\">
// Add the additional 'advanced' VTypes -- [Begin]
Ext.apply(Ext.form.VTypes, {
	daterange : function(val, field) {
		var date = field.parseDate(val);

		if(!date){
			return;
		}
		if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
			var start = Ext.getCmp(field.startDateField);
			start.setMaxValue(date);
			start.validate();
			this.dateRangeMax = date;
		}
		else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
			var end = Ext.getCmp(field.endDateField);
			end.setMinValue(date);
			end.validate();
			this.dateRangeMin = date;
		}
		/*
		 * Always return true since we're only using this vtype to set the
		 * min/max allowed values (these are tested for after the vtype test)
		 */
		return true;
	}
});
// Add the additional 'advanced' VTypes -- [End]

dateRangeFunc();
function dateRangeFunc()
	{
		// Date picker
		var fromdate = new Ext.form.DateField({
			format: 'Y-M-d', //YYYY-MMM-DD
			fieldLabel: '',
			id: 'startdt',
			name: 'startdt',
			width:140,
			allowBlank:false,
			vtype: 'daterange',
            endDateField: 'enddt'// id of the 'To' date field
		});

		var todate = new Ext.form.DateField({
			format: 'Y-M-d', //YYYY-MMM-DD
			fieldLabel: '',
			id: 'enddt',
			name: 'enddt',
			width:140,
			allowBlank:false,
			vtype: 'daterange',
            startDateField: 'startdt'// id of the 'From' date field
		});

		fromdate.render('fromdate');
		todate.render('todate');
} //dateRangeFunc() close
</script>";
    echo '<script type="text/javascript">'.$script.'</script>';
 echo'   <div>
  <div style="float:left;"><strong>From: </strong>
    <div id="fromdate"></div>
  </div>
  <div style="float:left; padding-left:20px;"><strong>To: </strong>
  	<div id="todate"></div>
  </div>
  <div style="clear:both"></div>
</div>';
?>