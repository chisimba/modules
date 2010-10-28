<?php

/**
 * html5input_class_inc.php
 *
 * Generates HTML5 input fields.
 *
 * PHP version 5
 *
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
 *
 * @category  Chisimba
 * @package   html5elements
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: html5input_class_inc.php -1   $
 * @link      http://avoir.uwc.ac.za
 */
class html5input extends object
{
    /**
     * Generates an HTML5 input element for text values.
     *
     * @access public
     * @param  string  $name        The name of the input field.
     * @param  string  $placeholder The placeholder text.
     * @param  string  $pattern     The input pattern.
     * @param  boolean $search      Is this a search field.
     * @param  boolean $required    Does a value need to be filled in before submission.
     * @param  boolean $autofocus   Should the focus automatically be on this field after pageload.
     * @return string  The generated HTML.
     */
    public function text($name, $placeholder=NULL, $pattern=NULL, $search=FALSE, $required=FALSE, $autofocus=FALSE)
    {
        $document = new DOMDocument();

        $input = $document->createElement('input');
        $input->setAttribute('type', $search ? 'search' : 'text');
        $document->appendChild($input);

        if (is_string($placeholder)) {
            $input->setAttribute('placeholder', $placeholder);
        }

        if (is_string($pattern)) {
            $input->setAttribute('pattern', $pattern);
        }

        if ($required) {
            $input->setAttribute('required', '');
        }

        if ($autofocus) {
            $input->setAttribute('autofocus', '');
        }

        return $document->saveHTML();
    }
}

?>
