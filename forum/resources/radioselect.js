/*

This JavaScript is taken from http://www.saila.com/attic/sandbox/selected_radio.html

I had to hard code labels[x+2] to make it work in the forum - Tohir

*/

var d = document;

function changeLabel()
{
    if(d.getElementsByTagName){
        var testForm = d.newTopicForm;
        var inputs = d.newTopicForm.discussionType;
        var labels = testForm.getElementsByTagName("label");
        for(x=0;x<inputs.length;x++){
            if(inputs[x].checked == true) {
                labelObj = labels[x+2]
                labelObj.style.background = "YELLOW" // and/or
                labelObj.style.fontWeight = "bold"
            } else {
                labelObj = labels[x+2]
                labelObj.style.background = "none" // and/or
                labelObj.style.fontWeight = "normal"
            }
        }
    } else{
        return
    }
}