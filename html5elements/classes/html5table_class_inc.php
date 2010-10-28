<?php

class html5table extends object
{
    /**
     * Generates an HTML5 table.
     *
     * @access public
     * @param  array  $data    The table contents.
     * @param  array  $headers The column headers.
     * @return string The markup for the table.
     */
    public function show(array $data, array $headers=array())
    {
        $document = new DOMDocument();
        $table = $document->createElement('table');

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

        $tbody = $document->createElement('tbody');
        $table->appendChild($tbody);

        foreach ($data as $row) {
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
}
