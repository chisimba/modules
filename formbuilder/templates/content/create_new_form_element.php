

<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->appendArrayVar('headerParams', $this->getJavascriptFile('1.4.2/jquery-1.4.2.min.js', 'jquery'));
$formName = $_REQUEST['formName'];
$formElementType = $_REQUEST['formElementType'];
$formElementName = $_REQUEST['formElementName'];


$objFormEntityHandler = $this->getObject('form_entity_handler','formbuilder');








    switch ($formElementType)
        {

            case 'radio':
                $postSuccess = $objFormEntityHandler->insertNewFormElement($formName,$formElementType,$formElementName);            
            break;

case 'checkbox':
     $postSuccess = $objFormEntityHandler->insertNewFormElement($formName,$formElementType,$formElementName);
    break;

case 'dropdown':
     $postSuccess = $objFormEntityHandler->insertNewFormElement($formName,$formElementType,$formElementName);
    break;
case 'label':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formName,$formElementType,$formElementName);
    break;
case 'HTML heading':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formName,$formElementType,$formElementName);
    break;
case 'date picker':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formName,$formElementType,$formElementName);
    break;
case 'text input':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formName,$formElementType,$formElementName);
    break;
case 'text area':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formName,$formElementType,$formElementName);
    break;
case 'button':
  $postSuccess = $objFormEntityHandler->insertNewFormElement($formName,$formElementType,$formElementName);
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



