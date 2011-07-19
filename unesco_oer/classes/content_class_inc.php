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

$this->loadClass('dropdown', 'htmlelements');

/**
 * A container class that describes product content in general. It contains the
 * basic features expected for the creation of content and must be extended for
 * use with products.
 *
 * @author Hermanus Brummer
 * @package unesco_oer
 */

class content extends object
{
    /**Describes the type that this content is, helps define what further types
     * of content can be contained within this content. If null then the further
     * types must be specified manually.
     *
     * @var string
     */
    protected $_content_type;

    /**Further types of content that can be contained within this content,
     *
     * @var array
     */
    protected $_content_types;

    /**A unique identifier that identifies this content within the platform
     *
     * @var string
     */
    protected  $_id;

    /**The path to the location of this content, constructed using the id's of
     * contents containing this content. This path together with this content's
     * id is unique. The top most id is the product id.
     *
     * @var string
     */
    protected  $_path;

    /**A human readable name for this content
     *
     * @var string
     */
    protected  $_title;

    /**This is an array of objects that inheret from the content class which
     * represent the contents of this content object
     *
     * @var array 
     */
    protected  $_contents;


    /**
     * * Method to display input
     *
     * @access   public
     * @abstract Override in subclasses.
     * @param    void
     * @return   void
     */
    public function showInput($prevAction = NULL)
    {
        $html = '';

        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "moduleHeader";

//        $newDropHeading = new htmlHeading();
//        $newDropHeading->str = 'Create new content: ';
//        //$html .= $newDropHeading->show();
//
//        $dropdown = new dropdown('new_dropdown');
//        $dropdown->addOption('none', 'nothing selected');
//        foreach ($this->_content_types as $key => $value) {
//            $dropdown->addOption(implode ( '__' , array($this->_path,$key) ), $value);
//        }
//        $dropdown->setSelected('none');
//
//        //$html .= $dropdown->show();
//        $table->startRow();
//        $table->addCell($newDropHeading->show() . $dropdown->show());
//
//
//        $editDropHeading = new htmlHeading();
//        $editDropHeading->str = 'Edit existing content: ';
//        //$html .= $editDropHeading->show();
//
//        //TODO Add method to Display existing contents
//        if (!empty($this->_contents)){
//            $dropdown = new dropdown('edit_dropdown');
//            $dropdown->addOption('none', 'nothing selected');
//            foreach ($this->_contents as $content) {
//                $dropdown->addOption($content->getFullPath(), $content->getTitle());
//            }
//            $dropdown->setSelected('none');
//
//            //$html .= $dropdown->show();
//
//            $table->addCell($editDropHeading->show() . $dropdown->show());
//        }
//        $table->endRow();

        //$html .= "<div class='root' ></div>";

        $heading = new htmlHeading();
        $heading->str = 'Create and Edit contents of product:';
        $heading->type = 1;

//        $fieldset = $this->newObject('fieldset','htmlelements');
//        $fieldset->setLegend('Options:');
//        //$fieldset->addContent($html);
//        $fieldset->addContent($table->show());

        $buttonSubmit = new button('done', 'Done');
        $actionURI = $this->uri(array('action' => 'ViewProduct', 'id' => $this->getPath()));
        $buttonSubmit->setOnClick('javascript: window.location=\'' . $actionURI . '\'');

        return '<div id="productmetaheading">'.$heading->show(). '</div>' . "<div class='root' ></div>" . $buttonSubmit->show();
        //return $html;
    }

    /**This returns the input for a given content ID
     *
     * @param string $id
     * @return string
     */
    public function showInputByContentID($id) //TODO implement this as a recursive algorithm
    {
        return $this->_contents[$id]->showInput();
    }

    //
    public function showInputByContentPath($path, $level = 0)
    {
        $tempContent = $this->getContentByContentPath($path);
        return $tempContent->showInput();
    }

    public function generateNewContent($contentPath)
    {
        $contentPathArray = $this->getPathArray($contentPath);
        $contentType = array_pop($contentPathArray);
        $newContent = $this->newObject($contentType);
        
        $newContentPath = implode('__', $contentPathArray); //TODO Extract this implode to a human readable function
        $newContent->setPath($newContentPath);
        return $newContent;
    }

    ////////// Getters //////////

    public function getTitle()
    {
        return $this->_title;
    }

    public function getType()
    {
        return $this->_content_type;
    }

    public function getPathArray($path)
    {
        if (!empty($path)){
            return explode("__", $path);
        }else{
            return explode("__", $this->getPath());
        }
    }

    public function getFullPath()
    {
        return $this->getPath() . '__' . $this->getID();
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function getID()
    {
        return $this->_id;
    }

    ////////// Setters //////////

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function setType($content_type)
    {
        $this->_content_type = $content_type;
    }

    /**This function sets the valid types of for this content to contain.
     * It expects and array of descriptive values of the content types with their
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

    public function setPath($path)
    {
        $this->_path = $path;
    }

    public function init() {
        $this->setType(NULL);
    }

    /**This is an abstract function intended for use with classes that extend this
     * content class
     *
     *
     */
    public function handleUpload() {
        return FALSE;
    }

    /**This function must be overridden by inhereting classes. It recieves the ID
     * of the container containing the contents and returns all instances of said
     * contents that have the ID as its container.
     *
     * @param string $containerID
     * @return content[]
     */
    function loadContent($containerID = NULL) {

        if (empty($containerID)){
            $id = $this->getID();
            if (empty($id)) {
                $containerID = $this->getPath();
            } else {
                $containerID = $id;
            }
        }

        $this->_contents = array();

        foreach ($this->_content_types as $class => $description) {
            $tempContent = $this->getObject($class);
            $tempArray = $tempContent->loadContent($containerID);
            $this->_contents = array_merge(
                    $this->_contents,
                    $tempArray
                    );
        }

        return $this->_contents;
    }

    function getContentByContentPath($path, $level = 0) //TODO think about moving some functionality to a protected method so users can't insert the level
    {
        $pathArray = $this->getPathArray($path);

        if (($level == 0) && (strcmp($pathArray[$level], $this->getPath()) == 0)) {
            foreach ($this->_contents as $content) {
                $output = $content->getContentByContentPath($path, $level+1);
                if (!empty($output)) return $output;
            }
            //If loop completes, then the content was not found
            return FALSE;
        } else {
            if (strcmp($pathArray[$level], $this->getID()) == 0){
                return $this;
            }else{
                foreach ($this->_contents as $content) {
                    $output = $content->getContentByContentPath($path, $level+1);
                    if (!empty($output)) return $output;
                }
            }
        }
    }

    function hasContents(){
        return !empty($this->_contents);
    }

    function getContentTree($editable = FALSE) {

        $output = '';

        $output .= '<script src="core_modules/tree/resources/TreeMenu.js" language="JavaScript" type="text/javascript"></script>';

        $objSkin = $this->getObject('skin', 'skin');
        $this->loadClass('treemenu', 'tree');
        $this->loadClass('treenode', 'tree');
        $this->loadClass('dhtml', 'tree');

        $icon = 'folder.gif';
        $expandedIcon = 'folder-expanded.gif';


//Create a new tree
        $menu = new treemenu();
        
//Add nodes to the tree
        $menu->addItem($this->getTreeNodes($editable));

// Create the presentation class

        $treeMenu = &new dhtml($menu, array('images' => $objSkin->getSkinURL() . 'treeimages/imagesAlt2', 'defaultClass' => 'treeMenuDefault'));

        $output .= $treeMenu->getMenu();

        return $output;
    }

    function getTreeNodes($editable = FALSE) {

        $node = new treenode(array('text' => $this->getTitle(), 'link' => "#", 'icon' => $icon, 'expandedIcon' => $expandedIcon, 'expanded' => FALSE), array('onclick' => "javascript: edit('{$this->getFullPath()}');", 'onexpand' => "alert('Expanded')"));

        foreach ($this->_contents as $content){
            $node->addItem($content->getTreeNodes($editable));
        }

        if ($editable){
            foreach ($this->_content_types as $key => $value) {
                $node->addItem(new treenode(array(
                                                'text' => 'new '. $value,
                                                'link' => "#", 'icon' => $icon,
                                                'expandedIcon' => $expandedIcon,
                                                'expanded' => FALSE),
                                            array(
                                                'onclick' => "javascript: newSection('".implode ( '__' , array($this->getFullPath(),$key) )."');",
                                                'onexpand' => "alert('Expanded')")
                                            ));
            }
        }

        return $node;
    }
}

?>
