<?php
require_once __DIR__ . "/../utils/Model.php";

class Dashboard extends Model
{
    protected $tableName = 'dashboard';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'description'];

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function create($data)
    {
        return true;
    }

    public function find($id)
    {
        return true;
    }

    public function update($id, $data)
    {
        return true;
    }

    public function delete($id)
    {
        return true;
    }

    public function getAll()
    {
        return  ['name' => 'Dashboard', 'description' => 'This is the dashboard model'];
        // return true;
    }

    public function validate($data)
    {
        return [];
    }
}