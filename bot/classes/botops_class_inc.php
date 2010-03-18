<?php

class botops extends object
{
    protected $objAibot;
    protected $objLanguage;
    protected $objMultisearch;

    public function init()
    {
        $this->objAibot       = $this->getObject('aibot', 'aibot');
        $this->objLanguage    = $this->getObject('language', 'language');
        $this->objMultisearch = $this->getObject('multisearchops', 'multisearch');
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
                if ($this->objAibot->isEnabled()) {
                    $response = $this->objAibot->chat($message);
                } else {
                    $response = $this->objLanguage->languageText('mod_bot_invalidcommand', 'bot');
                }
        }

        return $response;
    }
}
