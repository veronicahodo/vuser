<?php

// vobj.php - Vobj Object Handler

// version 1.0.0

require_once('vcrud.php');

class Vobj
{
    protected $crud;
    protected $table;
    protected $index;
    public $fields;

    function __construct(Vcrud $crud)
    {
        $this->crud = $crud;
        $this->table = '';
        $this->index = '';
    }

    function load($id)
    {
        $results = $this->crud->read($this->table, [[$this->index, '=', $id]]);
        if (count($results) != 1) {
            return false;
        }

        $this->fields = $results[0];
    }

    function save()
    {
        if (!empty($this->fields[$this->index])) {
            $this->crud->update($this->table, $this->fields, [[$this->index, '=', $this->fields[$this->index]]]);
            return true;
        } else {
            $this->crud->create($this->table, $this->fields);
            return true;
        }
    }
}
