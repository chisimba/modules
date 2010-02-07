<?php

class tripleobject extends object
{
    protected $id;
    protected $objTriplestore;
    protected $value;

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
        $this->objTriplestore->update($this->id, FALSE, FALSE, $value);
        $this->value = $value;
    }

    public function populate($id, $value)
    {
        $this->id    = $id;
        $this->value = $value;
    }
}
