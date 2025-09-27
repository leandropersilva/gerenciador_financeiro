<?php

abstract class Model
{
    protected $tablename;
    protected $primaryKey = 'id';
    protected $isFillable = [];
    protected $data = [];

    abstract protected function getTableName();
    abstract protected function getFillable();

    abstract public function create($array);
    abstract public function find($id);
    abstract public function update($int, $data);
    abstract public function delete($id);
    abstract public function all();

    abstract public function validade($data);
    abstract protected function getRules();
    abstract protected function getRelations();
}
