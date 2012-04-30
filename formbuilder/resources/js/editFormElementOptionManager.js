function hideFormElementOptionSuperContainer(){
    $(".formElementOptionsFormSuperContainer").hide();
    $(".formElementOptionsFormSuperContainer").children().hide(); 
    $(".formElementOptionUpdateHeading").hide();
}

function setUpEditLinkForMovableOptions(formElementType,formElementLabel){
    $(".editOptionLink").click(function(){
        
        alert($(this).parent().attr("labelorientation"));
        
        $("#editFormElementTabs").fadeOut("fast");
        $(".formElementOptionsFormSuperContainer").show();
        $(".editFormElementOptionsSideSeperator").toggle( "blind", {}, 1500 );
        $(".editFormElementOptionsHeadingSpacer").toggle("slide",{},1500,function(){
           createFormElementOptionFormHeading(true,formElementLabel);
           
           $(".formElementOptionUpdateHeading").fadeIn("slow"); 
           $(".editFormElementFormContainer").fadeIn("slow");
        });
        createButtonsForFormElementOptionsForm("Update "+formElementLabel+" Option Parameters");
        setUpCancelButton();
        
    });
}

function setUpCancelButton(){
    $("#formElementOptionCancelButton").click(function(){
        $(".formElementOptionUpdateHeading").fadeOut("slow"); 
        $(".editFormElementFormContainer").fadeOut("slow");
        $(".editFormElementOptionsSideSeperator").toggle( "blind", {}, 1000 );
        $(".editFormElementOptionsHeadingSpacer").toggle("slide",{},1000,function(){
            
            
            clearFormElementOptionFormHeading();
            clearButtonsForFormElementOptionsForm();
            $("#editFormElementTabs").fadeIn();
        });
    });
}


function createFormElementOptionFormHeading(edit,formElementLabel){
    if (edit == true){
      $(".formElementOptionUpdateHeading").html("Edit "+formElementLabel+" Option");  
    } else {
      $(".formElementOptionUpdateHeading").html("Add New "+formElementLabel+" Option");   
    }
}

function clearFormElementOptionFormHeading(){
     $(".formElementOptionUpdateHeading").html("");     
}

function createButtonsForFormElementOptionsForm(actionButtonLabel){
   $(".formElementOptionsFormButtonsContainer").empty();
   
   $(".formElementOptionsFormButtonsContainer").append("<button id='formElementOptionCancelButton' style='float:right; margin-left:15px;margin-right:15px;'>Cancel</button>");
   $(".formElementOptionsFormButtonsContainer").append("<button id='formElementOptionActionButton' style='float:right; margin-left:15px;margin-right:15px;'>"+actionButtonLabel+"</button>");
   $("#formElementOptionActionButton").button();
   $("#formElementOptionCancelButton").button();
}

function clearButtonsForFormElementOptionsForm(){
   $(".formElementOptionsFormButtonsContainer").empty(); 
}


