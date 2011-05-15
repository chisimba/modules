<?php

/**
 * html5form_class_inc.php
 *
 * Generates common HTML elements.
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
 * @copyright 2011 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */
class html5common extends object
{
    /**
     * Generates a paragraph containing text.
     *
     * @access public
     * @param  object $documnt The DOMDocument to use.
     * @param  string $text    The text.
     * @return object The generated DOMElement.
     */
    public function paragraph(DOMDocument $document, $text)
    {
        $p = $document->createElement('p');
        $p->appendChild($document->createTextNode($text));

        return $p;
    }
}

?>
