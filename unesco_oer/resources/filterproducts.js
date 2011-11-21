/* 
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation; either version 2 of the License, or 
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the 
 * Free Software Foundation, Inc., 
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


function ajaxFunction23(id,ProdID,browsecheck,institutionId){
    
    var ajaxRequest;

    try{
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e){
        // Internet Explorer Browsers
        try{
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e){
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }
    // Create a function that will receive data sent from the server
    ajaxRequest.onreadystatechange = function(){
        if(ajaxRequest.readyState == 4){
            var ajaxDisplay = document.getElementById('filternumDiv');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
                        
        }
    }
    var theme = document.getElementById('input_ThemeFilter').value;
    var lang = document.getElementById('input_LanguageFilter').value;
    var auth = document.getElementById('input_AuthorFilter').value;
    var numperpage = document.getElementById('input_NumFilter').value;
    var model = document.getElementById('input_Model').checked;
    var handbook = document.getElementById('input_Handbook').checked;
    var guide = document.getElementById('input_Guide').checked;
    var manual = document.getElementById('input_Manual').checked;
    var bestprac = document.getElementById('input_BestPractices').checked;
    var sort = document.getElementById('input_SortFilter').value
    var institution = document.getElementById('input_InstitutionFilter').value
      var region = document.getElementById('input_RegionFilter').value
        var country = document.getElementById('input_CountryFilter').value
    var adaptation = document.getElementById('searchpage').title;
    
   




              

    var queryString = "theme=" + theme + "&lang=" + lang + "&auth=" + auth  + "&numperpage=" + numperpage + "&models=" + model + "&guide=" + guide + "&handbook=" + handbook + "&manual=" + manual + "&bestprac=" + bestprac + "&id=" + id + "&sort=" + sort +"&adaptation=" + adaptation  +"&browsecheck=" + browsecheck +"&ProdID=" + ProdID + "&inst=" + institution + "&Reg=" + region + "&Country=" + country + "&institutionid=" + institutionId;
     
    ajaxRequest.open("GET", "index.php?module=unesco_oer&action=JavaFilternum&" + queryString, true);
    ajaxRequest.send(null);
     
    ///jQuery.get("index.php?moduler=unesco_oer&action=JavaFilter&" + queryString, 
    
    //function(data) {
      //jQuery('div.filterS').html(data);
      
   // });

}

    
    
    
    
    













function ajaxFunction(id,ProdID,browsecheck,institutionId){
    var ajaxRequest;

    try{
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e){
        // Internet Explorer Browsers
        try{
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e){
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }
    // Create a function that will receive data sent from the server
    ajaxRequest.onreadystatechange = function(){
        if(ajaxRequest.readyState == 4){
            var ajaxDisplay = document.getElementById('filterDiv');
            ajaxDisplay.innerHTML = ajaxRequest.responseText;
                        
        }
    }
    var theme = document.getElementById('input_ThemeFilter').value;
    var lang = document.getElementById('input_LanguageFilter').value;
    var auth = document.getElementById('input_AuthorFilter').value;
    var numperpage = document.getElementById('input_NumFilter').value;
    var model = document.getElementById('input_Model').checked;
    var handbook = document.getElementById('input_Handbook').checked;
    var guide = document.getElementById('input_Guide').checked;
    var manual = document.getElementById('input_Manual').checked;
    var bestprac = document.getElementById('input_BestPractices').checked;
    var sort = document.getElementById('input_SortFilter').value
    var institution = document.getElementById('input_InstitutionFilter').value
    var region = document.getElementById('input_RegionFilter').value
    var country = document.getElementById('input_CountryFilter').value
    var adaptation = document.getElementById('searchpage').title;



     
              

    var queryString = "theme=" + theme + "&lang=" + lang + "&auth=" + auth  + "&numperpage=" + numperpage + "&model=" + model + "&guide=" + guide + "&handbook=" + handbook + "&manual=" + manual + "&bestprac=" + bestprac  + "&sort=" + sort +"&adaptation=" + adaptation  +"&browsecheck=" + browsecheck +"&ProdID=" + ProdID   +"&id=" + id + "&inst=" + institution + "&Reg=" + region + "&Country=" + country + "&institutionid=" + institutionId ;
     
    ajaxRequest.open("GET", "index.php?module=unesco_oer&action=JavaFilter&" + queryString, true);
    ajaxRequest.send(null);
     
   

}






function bookmarksave(time,parentid,userid,textname,commentboxname){
    var ajaxRequest;

    try{
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e){
        // Internet Explorer Browsers
        try{
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e){
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }
    
    var temptext = "input_" + textname;
    var tempcomment = "input_" + commentboxname;
    
  
    var Title = document.getElementById(temptext).value;
    var Description = document.getElementById(tempcomment).value;

    
    if ((Title == "") || (Description =="")){
        alert("Please fill in the required fields")
            
    }
    
    else {
         
    var queryString = "Title=" + Title + "&Description=" + Description  + "&time=" + time  + "&parentid=" + parentid + "&userid=" + userid ;
     
    ajaxRequest.open("GET", "index.php?module=unesco_oer&action=BookmarkData&" + queryString, true);
    ajaxRequest.send(null);
     
    }

}

function bookmarkupdate(time,textname,commentboxname,bookmarkid){
    var ajaxRequest;

    try{
        // Opera 8.0+, Firefox, Safari
        ajaxRequest = new XMLHttpRequest();
    } catch (e){
        // Internet Explorer Browsers
        try{
            ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try{
                ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e){
                // Something went wrong
                alert("Your browser broke!");
                return false;
            }
        }
    }
    
    var temptext = "input_" + textname;
    var tempcomment = "input_" + commentboxname;
    
  
    var Title = document.getElementById(temptext).value;
    var Description = document.getElementById(tempcomment).value;

    
    
    if ((Title == "") || (Description =="")){
        alert("Please fill in the required fields")
            
    }
    
    else {
         
    var queryString = "Title=" + Title + "&Description=" + Description  + "&time=" + time  + "&bookmarkid=" + bookmarkid;
     
    ajaxRequest.open("GET", "index.php?module=unesco_oer&action=Bookmarkupdate&" + queryString, true);
    ajaxRequest.send(null);
     
    }

}
