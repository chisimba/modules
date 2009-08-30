<?php

class formerror extends object {
    var $errorarray = array();
    function init() {

    }

    function setError($field, $error) {
        $this->errorarray[$field] = $error;
    }

    function getFormattedError($field){
        $errorContent="";
        if (isset($this->errorarray[$field])) {

            $errorContent= $this->errorarray[$field];
        }
        $errors=
        "Ext.onReady(function(){

        // basic tabs 1, built from existing content
        var tabs = new Ext.Panel({
        renderTo: 'errorscontent',
        title: 'Errors',
        width:400,
        height:300,
        activeTab: 0,
        frame:true,
        defaults:{autoHeight: true},
        items:[
            {

             html: '<font color=\"red\"><h3>There are errors in this document:</h3></font>".$errorContent."'

            }

        ]
    });


});
";
        $content="<center><div id=\"errorscontent\"><script type=\"text/javascript\">".$errors."</script></div></centent>";
        return $content;
    }
    function getError($field) {
        if (isset($this->errorarray[$field])) {
            //return "<span class=\"error\" style=\"color:red\">* " . $this->errorarray[$field] . "</span>";
            return "<span class=\"error\">* " . $this->errorarray[$field] . "</span>";
        }
        else {
            return "";
        }
    }
    function numErrors() {
        return count($this->errorarray);
    }
}

?>
