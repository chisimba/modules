<?php

class botops extends object
{
    protected $objLanguage;

    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
    }

    public function process($message)
    {
        $command   = strtolower(strtok($message, ' '));
        $arguments = strtok('');

        switch ($command) {
            case 'search':
                $query    = $this->objMultisearch->buildQuery($arguments, 2);
                $results  = $this->objMultisearch->doQuery($query);
                $text     = $this->objMultisearch->formatQuery($results, 'plaintext');
                $response = implode('', $text);
                break;
            default:
                $response = $this->objLanguage->languageText('mod_bot_invalidcommand', 'bot');
        }

        return $response;
    }
}
