<?php

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

        return $table->saveHTML();
    }
}
