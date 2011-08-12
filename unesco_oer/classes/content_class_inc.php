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
 * A abstract container class that describes product content in general. It
 * contains the basic features expected for the creation of content and must be
 * extended for use with products.
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

    /**The parent ID containing this content This id together with this content's
     * id is unique.
     *
     * @var string
     */
    protected  $_parentID;

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
     * @return   string
     */
    public function showInput($productID, $prevAction = NULL)
    {
        return "showInput has not been defined for {$this->getType()}!";
    }

    /**This returns the input for a given content ID
     *
     * @param string $id
     * @return string
     */
    public function showInputByContentID($id)
    {
        $tempContent = $this->getContentByContentID($id);
        return $tempContent->showInput();
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

    public function getPairArray()
    {
        return array($this->getParentID(), $this->getID());
    }

    public function getPairString()
    {
        return $this->getParentID() . '__' . $this->getID();
    }

    public function getParentID()
    {
        return $this->_parentID;
    }

    public function getParent() // TODO write this function so that it returns an instance of the parent!
    {
        return $this->_parentID;
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

    public function setParentID($id)
    {
        $this->_parentID = $id;
    }

    /**This is a function to initialize the object and should be overiddent by all
     * inheriting classes.
     *
     */
    public function init() {
        $this->setType(NULL);
    }

    /**This is an abstract function intended for use with classes that extend this
     * content class
     *
     * @access   public
     * @abstract Override in subclasses.
     * @param    void
     * @return   boolean
     */
    public function handleUpload() {
        return FALSE;
    }

    /**This function returns all instances of contents that have the ID of this
     * content as its parent.
     *
     * @param string $containerID 
     * @return content[]
     */
    public function getContents() {
        if (empty($this->_contents)){ 
            $this->_contents = array();
            foreach ($this->_content_types as $class => $description) {
                $tempContent = $this->newObject($class);
                $tempArray = $tempContent->getContentsByParentID($this->getID());
                $this->_contents = array_merge(
                        $this->_contents,
                        $tempArray
                        );
            }
        }

        return $this->_contents;
    }

    /**This function returns all instances of contents that have the given ID as
     * its Parent. All contents returned being of the same type as this content
     *
     * @access   public
     * @abstract Override in subclasses.
     * @param string $parentID
     * @return   array
     */
    public function getContentsByParentID($parentID) {
        return array();
    }

    /**This function should be overwritten by inheriting classes. It recieves the
     * unique id of the content or an array containing the data of the content. It
     * then loads the content using the given information.
     *
     * It returns FALSE if load is unsuccessful, TRUE otherwise.
     *
     * @access   public
     * @abstract Override in subclasses.
     * @param mixed $id
     * @return boolean
     */
    public function load($id) {
        return FALSE;
    }

    /**This function recursively searches through the loaded contents
     *
     * @param <type> $id
     * @return content
     */
    function getContentByContentID($id)
    {
        if (strcmp($id, $this->getID()) == 0)
        {
            return $this;
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

    function hasContents(){
        return !empty($this->_contents);
    }

    function getTreeNodes($editable = FALSE, $productID = NULL, $highlighted = FALSE) {

        $this->loadClass('treenode', 'tree');

        $icon = 'icon-product-closed-folder.png';
        $expandedIcon = 'icon-product-opened-folder.png';

        // Makes tree a link if not editing when adding product metadata
        if ($editable){
                        $node = new treenode(array(
                                                        'text' => $this->getTitle(),
                                                        'link' => "#", 'icon' => $icon,
                                                        'expandedIcon' => $expandedIcon,
                                                        'expanded' => FALSE),
                                                    array(
                                                        'onclick' => "javascript: edit('{$this->getPairString()}');",
                                                        'onexpand' => ""
                                                    ));
                      }
                      else{
                            $node = new treenode(array(
                                                        'text' => $this->getTitle(),
                                                        'cssClass' => ($highlighted ? 'HL' : ''),
                                                        'link' => $this->getViewLink($productID), 
                                                        'icon' => $icon,
                                                        'expandedIcon' => $expandedIcon,
                                                        'expanded' => FALSE)
                                                     );
                       
                      }

        foreach ($this->_contents as $content){ 
            $node->addItem($content->getTreeNodes($editable, $productID,$highlighted));
        }

        if ($editable){
            foreach ($this->_content_types as $class => $description) {
                $node->addItem(new treenode(array(
                                                'text' => $description,
                                                'link' => "#", 'icon' => 'icon-new-product.png',
                                                'expandedIcon' => $expandedIcon,
                                                'expanded' => FALSE),
                                            array(
                                                'onclick' => "javascript: newSection('".implode ( '__' , array($this->getID(),$class) )."');",
                                                'onexpand' => "alert('Expanded')")
                                            ));
            }
        }

        return $node;
    }

    function addNewContent($newContent){
        if (strcmp($newContent->getParentID(), $this->getID()) == 0){ //TODO add check for type validity
            $this->_contents[] = $newContent;
            return TRUE;
        } else {
            foreach ($this->_contents as $content) {
                $result = $content->addNewContent($newContent);
                if ($result) return $result;
            }
        }
    }
    
    function getViewLink($productID = NULL){
        return FALSE;
    }

    function copyContentsToParent($newParentID){
        $this->_id = NULL;
        $this->_parentID = $newParentID;
        $this->saveNew();

        foreach ($this->_contents as $content) {
            $content->copyContentsToParent($this->getID());
        }

        return $this;
    }

    protected function saveNew(){
    }

    protected function updateExisting(){
    }
    
    public function showReadOnlyInput(){
        return "Nothing to show!";
    }
}

?>
