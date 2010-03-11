<?php

class bot extends controller
{
    protected $objMultisearch;

    public function init()
    {
        $this->objMultisearch = $this->getObject('multisearchops', 'multisearch');
    }

    public function dispatch()
    {
        $body      = $this->getParam('body');
        $command   = strtolower(strtok($body, ' '));
        $arguments = strtok('');

        switch ($command) {
            case 'search':
                $query    = $this->objMultisearch->buildQuery($arguments);
                $results  = $this->objMultisearch->doQuery($query);
                $text     = $this->objMultisearch->formatQuery($results, 'plaintext');
                $response = implode('', $text);
                break;
            default:
                $response = 'No commands matching your query. Please try again.';
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
