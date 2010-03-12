<?php

class bot extends controller
{
    protected $objLanguage;
    protected $objMultisearch;

    public function init()
    {
        $this->objLanguage    = $this->getObject('language', 'language');
        $this->objMultisearch = $this->getObject('multisearchops', 'multisearch');
    }

    public function dispatch()
    {
        $body      = $this->getParam('body');
        $command   = strtolower(strtok($body, ' '));
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

        header('Content-Type: text/plain; charset=UTF-8');
        echo $response;
    }

    /**
     * Login is not required for this module.
     *
     * @access public
     * @param  string  $action The name of the action.
     * @return boolean Always returns FALSE.
     */
    public function requiresLogin($action)
    {
        return FALSE;
    }
}
