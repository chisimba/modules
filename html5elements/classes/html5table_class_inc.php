<?php

/**
 * html5table_class_inc.php
 *
 * Generates an HTML5 table to display tabular data on a web page.
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
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */
class html5table extends object
{
    /**
     * Generates an HTML5 table.
     *
     * @access public
     * @param  string $title    The title of the table. NULL for none.
     * @param  array  $headers  The column headers. Empty array for none.
     * @param  array  $contents The table contents. Empty array for none.
     * @return string The markup for the table.
     */
    public function show($title, array $headers, array $contents)
    {
        $document = new DOMDocument();

        $table = $document->createElement('table');
        $document->appendChild($table);

        if ($title !== NULL) {
            $caption = $document->createElement('caption');
            $table->appendChild($caption);

            $text = $document->createTextNode($title);
            $caption->appendChild($text);
        }

        if (count($headers) > 0) {
            $thead = $document->createElement('thead');
            $table->appendChild($thead);

            $tr = $document->createElement('tr');
            $thead->appendChild($tr);

            foreach ($headers as $header) {
                $th = $document->createElement('th');
                $tr->appendChild($th);

                $text = $document->createTextNode($header);
                $th->appendChild($text);
            }
        }

        if (count($contents) > 0) {
            $tbody = $document->createElement('tbody');
            $table->appendChild($tbody);

            foreach ($contents as $row) {
                $tr = $document->createElement('tr');
                $tbody->appendChild($tr);

                foreach ($row as $value) {
                    $td = $document->createElement('td');
                    $tr->appendChild($td);

                    $text = $document->createTextNode($value);
                    $td->appendChild($text);
                }
            }
        }

        return $document->saveHTML();
    }
}
