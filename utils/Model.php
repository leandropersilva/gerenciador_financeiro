<?php
abstract class Model
{
    protected $connection;
    protected $data = [];
    protected $originalData = [];
    protected $errors = [];

    protected $tableName;
    protected $primaryKey = 'id';
    protected $fillable = [];

    abstract protected function getTableName();
    abstract protected function getFillable();

    abstract public function create($data);
    abstract public function find($id);
    abstract public function update($id, $data);
    abstract public function delete($id);
    abstract public function getAll();
    abstract public function validate($data);


    public function __construct($connection = null)
    {
        $this->connection = $connection;
    }

    public function __get($property)
    {
        return $this->data[$property] ?? null;
    }

    public function __set($property, $value)
    {
        if (in_array($property, $this->getFillable())) {
            $this->data[$property] = $value;
        }
    }

    public function fill($data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->getFillable())) {
                $this->data[$key] = $value;
            }
        }
        return $this;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function isDirty()
    {
        return $this->data !== $this->originalData;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid()
    {
        $this->errors = $this->validate($this->data);
        return empty($this->errors);
    }
}
