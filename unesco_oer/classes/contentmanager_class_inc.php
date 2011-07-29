<?php
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

/**
 * the exception handler
 */
require_once 'classes/core/customexception_class_inc.php';

/**
 * Description of contentmanager_class_inc
 *
 * @author Hermanus Brummer
 * @package unesco_oer
 */
class contentmanager extends object
{
    /**This is the product ID associated with this content manager
     *
     * @var string
     */
    private $_productID;

    /**List of valid types of contetent for the product being associated with.
     * Has the form: array('class_name'=>'class_description', ...).
     *
     * @var array
     */
    private $_content_types;

    /**This is an array of the contents that is associated with the productID
     *
     * @var array
     */
    private $_contents;

    public function init() {
        //nothinfg to do here
    }

    /**Method to display input
     *
     * @access   public
     * @param    void
     * @return   string
     */
    public function showInput($prevAction = NULL)
    {
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

        $heading = new htmlHeading();
        $heading->cssClass = "greytext";
        $heading->str = 'Create and Edit contents of product:';
        $heading->type = 1;

        $buttonSubmit = new button('done', 'Done');
        $actionURI = $this->uri(array('action' => 'ViewProduct', 'id' => $this->getProductID()));
        $buttonSubmit->setOnClick('javascript: window.location=\'' . $actionURI . '\'');

        $output = '<div id="productmetaheading">';
        $output .= $heading->show();
        $output .= '</div>';
        $output .= "<div class='root' ></div>";
        $output .= $buttonSubmit->show();
        $output .= "<div class='product_id' id='{$this->getProductID()}'></div>";

        return $output;
    }

    public function generateNewContent($parentID_class_pair)
    {
        $parentID_class_array = $this->getPairArray($parentID_class_pair);
        $contentType = array_pop($parentID_class_array);
        $newContent = $this->newObject($contentType);
        $newContent->setParentID(array_pop($parentID_class_array));
        return $newContent;
    }

    public function getPairArray($parentID_class_pair)
    {
        return explode("__", $parentID_class_pair);
    }

    /**This function returns all instances of contents that have the ID of the
     * product as its parent ID
     *
     * @param string $containerID
     * @return content[]
     */
    function getAllContents() {
        if (empty ($this->_contents)){
            $this->_contents = array();
        
            foreach ($this->_content_types as $class => $description) {
                $tempContent = $this->newObject($class);
                $tempArray = $tempContent->getContentsByParentID($this->_productID);
                $this->_contents = array_merge(
                        $this->_contents,
                        $tempArray
                        );
            }
        }

        return $this->_contents;
    }

    /**This function recursively searches through the loaded contents
     *
     * @param <type> $id
     * @return content
     */
    function getContentByContentID($id)
    {
        if (empty ($this->_contents)){
            $this->getAllContents();
        }
        foreach ($this->_contents as $content)
        {
            if (strcmp($id, $content->getID()) == 0)
            {
                return $content;
            }
            else
            {
                $result = $content->getContentByContentID($id);
                if ($result != FALSE) return $result;
            }
        }

        return FALSE;
    }

    function getContentTree($editable = FALSE) { 

        $output = '';

        $output .= '<script src="core_modules/tree/resources/TreeMenu.js" language="JavaScript" type="text/javascript"></script>';

        $objSkin = $this->getObject('skin', 'skin');
        $this->loadClass('treemenu', 'tree');
        $this->loadClass('treenode', 'tree');
        $this->loadClass('dhtml', 'tree');


//Create a new tree
        $menu = new treemenu();

//Add nodes to the tree
        foreach ($this->_contents as $content){
            $menu->addItem($content->getTreeNodes($editable, $this->getProductID()));
        }

        if ($editable){
            foreach ($this->_content_types as $key => $value) {
                $menu->addItem(new treenode(array(
                                                'text' => $value,
                                                'link' => "#", 'icon' => 'icon-new-product.png',
                                                'expandedIcon' => $expandedIcon,
                                                'expanded' => FALSE),
                                            array(
                                                'onclick' => "javascript: newSection('".implode ( '__' , array($this->getProductID(),$key) )."');",
                                                'onexpand' => "alert('Expanded')")
                                            ));
            }
        }

// Create the presentation class

        $treeMenu = &new dhtml($menu, array('images' => $objSkin->getSkinURL() . 'images/tree', 'defaultClass' => 'treeMenuDefault'));

        $output .= $treeMenu->getMenu();

        return $output;
    }

    function  setProductID($id)
    {
        $this->_productID = $id;
    }

    /**This function sets the valid types of for this content manager to contain.
     * It expects an array of descriptive values of the content types with their
     * class names as keys.
     *
     * @param <type> $types
     */
    public function setValidTypes($types)
    {
        if (!is_array($types)){
            $types = array($types);
        }

        $this->_content_types = $types;
    }

    function hasContents(){
        return !empty($this->_contents);
    }

    function getProductID(){
        return $this->_productID;
    }

    function addNewContent($newContent){
        if (strcmp($newContent->getParentID(), $this->getProductID()) == 0){ //TODO add check for type validity
            $this->_contents[] = $newContent;
            return TRUE;
        } else {
            foreach ($this->_contents as $content) {
                $result = $content->addNewContent($newContent);
                if ($result) return $result;
            }
        }
    }
}
?>
