<?php

class tripleobject extends object
{
    protected $id;
    protected $value;
    protected $objTriplestore;

    public function init()
    {
        $this->objTriplestore = $this->getObject('dbtriplestore', 'triplestore');
    }

    public function delete()
    {
        $this->objTriplestore->delete($this->id);
    }

    public function edit($value)
    {
        $this->value = $value;
        $this->objTriplestore->update($this->id, FALSE, FALSE, $value);
    }

    public function populate($id, $value)
    {
        $this->id    = $id;
        $this->value = $value;
    }
}
