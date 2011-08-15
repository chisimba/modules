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

//function AddItem(Text,Value)
//{
//    // Create an Option object
//
//    var opt = document.createElement("option");
//
//    // Add an Option object to Drop Down/List Box
//    document.getElementById("DropDownList").options.add(opt);
//
//    // Assign text and value to Option object
//    opt.text = Text;
//    opt.value = Value;
//
//}

function toggle(element) {
    document.getElementById(element).style.display = (document.getElementById(element).style.display == "none") ? "" : "none";
}

function toggleRelationDropDown(nameOfDropDown1, nameOfDropDown2)
{
    var objDrop1 = document.getElementById(nameOfDropDown1);
    var objDrop2 = document.getElementById(nameOfDropDown2);
    
    if ( objDrop1.options[objDrop1.selectedIndex].value == '')
    {
        objDrop2.disabled=true;
        addOption(objDrop2, 'disabled', 'disabled');
        setSelectionByValue(objDrop2, 'disabled');
    }
    else
    {
        objDrop2.disabled=false;
        removeOptionsByValue(objDrop2, 'disabled');
    }

}

function toggleInstitutionDropDown(value,dropdowndiv, productID)
{
    //alert($('#' + dropdowndiv).html());
    $('#' + dropdowndiv).load('index.php?module=unesco_oer&action=saveProductMetaData&add_product_submit=getinstitutions&productID='+ productID +'&group_id=' + value);
}


function SubmitProduct(element, value)
{
    var objForm = document.forms['add_products_ui'];
    objForm.elements[element].value = value;
    objForm.submit();
}

function addOption(dropdown, text, value )
{
var optn = document.createElement("OPTION");
optn.text = text;
optn.value = value;
dropdown.options.add(optn);
}

function setSelectionByValue(dropdown, svalue){
    for (var i=0; i < dropdown.length; i++)
    {
        if (dropdown[i].value == svalue)
        {
            dropdown[i].selected = true;
        }
    }
}

function removeOptionsByValue(selectbox, svalue)
{
    var i;
    for(var i=0; i < selectbox.length; i++)
    {
    if(selectbox[i].value == svalue)
        selectbox.remove(i);
    }
}

window.onload = function() {
   toggleRelationDropDown('input_relation_type', 'input_relation');
}

function viewProduct(selectName) {
    
    var objDrop = document.getElementById(selectName);
    var id = objDrop.options[objDrop.selectedIndex].value;
    
    document.location.href='/unesco_oer/index.php?action=ViewProduct&id=' + id;
}