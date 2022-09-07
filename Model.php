<?php

class Model
{
    public $table = '';
    function __construct()
    {
        $this->table = $this->table ?: Inflect::pluralize(strtolower(get_class($this)));
    }

    public function all() {
        return "Calling all function of {$this->table} table";
    }
}
