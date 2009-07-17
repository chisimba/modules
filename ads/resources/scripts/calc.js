/* javascript */
jQuery(document).ready(function() {
   
   // on page load, do calculations
   checkInput5();
   checkInput6();
   checkInput8();
   checkInput9();

   jQuery("#input_D5_1").blur(function() {
       checkInput5();
       checkInput6();
   });
   jQuery("#input_D5_2").blur(function() {
       checkInput5();
       checkInput6();
   });
   jQuery("#input_D5_3").blur(function() {
       checkInput5();
   });
   jQuery("#input_D5_4").blur(function() {
       checkInput5();
   });
   jQuery("#input_D5_5").blur(function() {
       checkInput5();
   });

   jQuery("#input_D5_6").blur(function() {
       checkInput6();
   });
   
   jQuery("#input_D5_7").blur(function() {
       checkInput8();
   });
   jQuery("#input_D5_8").blur(function() {
       checkInput8();
   });
   jQuery("#input_D5_9").blur(function(){
       checkInput9();
   });
});

function checkInput5() {
   /*
    * state = 0: action on page load
    * state = 1: action on input blur
    */
    // check all the input fields from 1 to 8
       var inp1 = parseInt(jQuery("#input_D5_1").val()),
           inp2 = parseInt(jQuery("#input_D5_2").val()),
           inp3 = parseInt(jQuery("#input_D5_3").val()),
           inp4 = parseInt(jQuery("#input_D5_4").val()),
           inp5 = parseInt(jQuery("#input_D5_5").val());

       if(isNaN(inp1)) {inp1 = 0;}
       if(isNaN(inp2)) {inp2 = 0;}
       if(isNaN(inp3)) {inp3 = 0;}
       if(isNaN(inp4)) {inp4 = 0;}
       if(isNaN(inp5)) {inp5 = 0;}
       // do the calculations
       var totalContactTime = inp1*(inp2 + inp3 + inp4 + inp5);
       jQuery("#totContTime").text(totalContactTime);
}

function checkInput6() {
    var inp1 = parseInt(jQuery("#input_D5_1").val()),
        inp2 = parseInt(jQuery("#input_D5_2").val()),
        inp6 = parseInt(jQuery("#input_D5_6").val());

    if(isNaN(inp1)) {inp1 = 0;}
    if(isNaN(inp2)) {inp2 = 0;}
    if(isNaN(inp6)) {inp6 = 0;}
    // calculate the total notional study
    var totNotStudy = inp1*inp2*inp6;
    jQuery("#totNotTime").text(totNotStudy);
}
       
function checkInput8() {
    var inp7 = parseFloat(jQuery("#input_D5_7").val()),
        inp8 = parseFloat(jQuery("#input_D5_8").val());

    if(isNaN(inp7)) {inp7 = 0;}
    if(isNaN(inp8)) {inp8 = 0;}
    
    // calculate the total notional study
    var totExamTime = inp7*inp8;
    jQuery("#totExamTime").text(totExamTime);
}

function checkInput9() {
    // get the total notional hours (excluding studying)
    var totNotHrs1 = parseInt(jQuery("#totNotTime").text()),
        totNotHrs2 = parseInt(jQuery("#input_D5_9").val());

    if(isNaN(totNotHrs1)) {totNotHrs1 = 0;}
    if(isNaN(totNotHrs2)) {totNotHrs2 = 0;}

    var totNotHrs = totNotHrs1 + totNotHrs2;
    jQuery("#totNotTime2").text(totNotHrs);
}