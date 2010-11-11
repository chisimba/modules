
<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$formNumber = $_REQUEST['formNumber'];
$formName = $_REQUEST['formName'];
$formElementType = $_REQUEST['formElementType'];
$formElementName = $_REQUEST['formElementName'];


$objFormEntityHandler = $this->getObject('form_entity_handler','formbuilder');








    switch ($formElementType)
        {

            case 'radio':
                $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
            break;

case 'checkbox':
     $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
    break;

case 'dropdown':
     $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
    break;
case 'label':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
    break;
case 'HTML_heading':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
    break;
case 'datepicker':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
    break;
case 'text_input':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
    break;
case 'text_area':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
    break;
case 'button':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
    break;
case 'multiselectable_dropdown':
 $postSuccess = $objFormEntityHandler->insertNewFormElement($formNumber,$formName,$formElementType,$formElementName);
        break;
    default:
                $postSuccess = 2;
            break;
               // $this->clickedAdd=$this->getParam('clickedadd');

                //return "home_tpl.php";
        }



?>

<html>
    <div id="postSuccess">
        <?php
        echo $postSuccess;
        ?>
    </div>

</html>



