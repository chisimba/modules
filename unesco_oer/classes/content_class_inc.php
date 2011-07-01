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

        $dropdown = new dropdown('new_dropdown');
        $dropdown->addOption('none', 'nothing selected');
        foreach ($this->_content_types as $value) {
            $dropdown->addOption(implode ( '__' , array($this->_path,'new') ), $value);
        }
        $dropdown->setSelected('none');

        $html .= $dropdown->show();

        $html .= "<div class='root' style='display: none;' ></div>";

        $this->_contents;

        return $html;
    }

    public function showInputByContentID($id)
    {
        return $this->_content_types[$id];
    }

    public function showInputByContentPath($path)
    {
        $array = $this->getPathArray($path);
        $lastContent = array_pop($array);

        if (strcmp($lastContent, "new") == 0){
            $content = $this->getObject($this->_content_types[0]);
            return $content->showInput();
        }
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
        return explode("__", $path);
    }

    ////////// Setters //////////

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function setType($node_type)
    {
        $this->_content_type = $node_type;
    }

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
}

?>
